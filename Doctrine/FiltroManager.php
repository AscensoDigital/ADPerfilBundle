<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-06-15
 * Time: 09:33 AM
 */

namespace AscensoDigital\PerfilBundle\Doctrine;


use AscensoDigital\PerfilBundle\Configuration\Configurator;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FiltroManager{

    protected $requestStack;
    protected $configurator;
    protected $filtrosNormalized;
    protected $excludes;
    protected $qb;

    private $procesado=false;

    public function __construct(RequestStack $requestStack, Configurator $configurator) {
        $this->requestStack = $requestStack;
        $this->configurator = $configurator;
    }

    public function addNormalizedFiltro($keyField, $filtroNormal) {
        if(is_null($this->filtrosNormalized)) {
            $this->filtrosNormalized=array();
        }
        $this->filtrosNormalized[$keyField]=$filtroNormal;
    }

    public function addNormalizedFiltros(array $filtrosAdd){
        if(is_null($this->filtrosNormalized)) {
            $this->filtrosNormalized=$filtrosAdd;
        }
        else {
            $this->filtrosNormalized=$filtrosAdd + $this->filtrosNormalized;
        }
    }

    public function addExcludes(array $excludesAdd){
        if(is_array($this->excludes)) {
            $this->excludes=$this->excludes+$excludesAdd;
        }
        else {
            $this->excludes=$excludesAdd;
        }
    }

    public function getFiltros($route=null) {
        $request=$this->getRequest();
        if(is_null($route)){
            $route=$request->attributes->get('_route');
        }
        $filtros=is_null($request) ? array() : $request->get('ad_perfil_filtros',$request->get('filtros_'.$route,$request->getSession()->get('filtros_'.$route,array())));
        $request->getSession()->set('filtros_'.$route, $filtros);
        return $filtros;
    }

    public function procesa() {
        $filtros=$this->getFiltros();
        $ret=array();
        foreach($filtros as $filName => $data){
            $alias=$this->getAlias($filName);
            if(is_array($alias)){
                foreach ($alias as $keySubitem => $aliasSubitem) {
                    if(isset($data[$keySubitem])) {
                        $valor=$this->procesaValor($data[$keySubitem], $this->getType($filName));
                        if(!is_null($valor)) {
                            $ret[$aliasSubitem] = $valor;
                            $this->normalizar($filName,$aliasSubitem,$valor);
                        }
                    }
                }
            }
            else {
                $valor=$this->procesaValor($data, $this->getType($filName));
                if(!is_null($valor)) {
                    $ret[$alias] = $valor;
                    $this->normalizar($filName,$alias,$valor);
                }
            }
        }
        $this->procesado=true;
        return $ret;
    }

    public function getQueryBuilder(QueryBuilder $qb, $isNull=false) {
        $this->addExcludes(array('token'));
        if(false===$this->procesado){
            $this->procesa();
        }
        foreach ($this->filtrosNormalized as $field => $data) {
            if(!in_array($field,$this->excludes)) {
                $operator=$data['operator'];
                switch($data['type']) {
                    /*case 'fecha':
                        $qb->andWhere($qb->expr()->eq('DATE('.$value['campo'].')','DATE(:fecha)'))
                            ->setParameter(':fecha',$value['valor']);
                        break;
                    case 'fechaRango':
                        if(isset($value['desde']) and isset($value['hasta'])) {
                            $qb->andWhere($qb->expr()->between('DATE('.$value['campo'].')',':fecha_desde',':fecha_hasta'))
                                ->setParameter(':fecha_desde',$value['desde'])
                                ->setParameter(':fecha_hasta',$value['hasta']);
                        }
                        elseif(isset($value['desde'])){
                            $qb->andWhere($qb->expr()->gte('DATE('.$value['campo'].')','DATE(:fecha_desde)'))
                                ->setParameter(':fecha_desde',$value['desde']);
                        }elseif(isset($value['hasta'])){
                            $qb->andWhere($qb->expr()->lte('DATE('.$value['campo'].')','DATE(:fecha_hasta)'))
                                ->setParameter(':fecha_hasta',$value['hasta']);
                        }
                        break;*/
                    default:
                        if(isset($data['function'])){
                            if(true===$isNull){
                                $qb->andWhere($qb->expr()->orX($qb->expr()->isNull($field), $qb->expr()->$operator($data['function'].'('.$field.')',$data['function'].'(:valor_'.$data['key'].')')))
                                    ->setParameter(':valor_'.$data['key'], $data['value']);
                            }
                            else {
                                $qb->andWhere($qb->expr()->$operator($data['function'].'('.$field.')', $data['function'].'(:valor_' . $data['key'].')'))
                                    ->setParameter(':valor_' . $data['key'], $data['value']);
                            }
                        }
                        else {
                            if(true===$isNull){
                                $qb->andWhere($qb->expr()->orX($qb->expr()->isNull($field), $qb->expr()->$operator($field,':valor_'.$data['key'])))
                                    ->setParameter(':valor_'.$data['key'], $data['value']);
                            }
                            else {
                                $qb->andWhere($qb->expr()->$operator($field, ':valor_' . $data['key']))
                                    ->setParameter(':valor_' . $data['key'], $data['value']);
                            }
                        }
                }
            }
        }
        return $qb;
    }

    /**
     * @return null|Request
     */
    protected function getRequest(){
        return $this->requestStack->getCurrentRequest();
    }

    protected function getAlias($filtroName) {
        $filtro=$this->configurator->getFiltroConfiguration($filtroName);
        return  $filtro['table_alias'];
    }

    protected function getType($filtroName) {
        $filtro=$this->configurator->getFiltroConfiguration($filtroName);
        return $filtro['type'];
    }

    private function normalizar($filtroName, $table_alias, $valor) {
        $filtroConf = $this->configurator->getFiltroConfiguration($filtroName);
        $tmp = array();
        $fieldName = $table_alias.'.'.$filtroConf['field'];
        $tmp['operator'] = $filtroConf['operator'];
        $tmp['type'] = $filtroConf['type'];
        $tmp['value'] = $valor;
        $tmp['key']=$table_alias;
        if(isset($filtroConf['function'])) {
            $tmp['function']=$filtroConf['function'];
        }
        $this->addNormalizedFiltro($fieldName,$tmp);
    }

    private function procesaValor($valor,$type) {
        if(is_array($valor)){
            if(count($valor)>1 || $valor[0]>0){
                if($valor[0]=="") {
                    unset($valor[0]);
                }
                return $valor;
            }
        }
        elseif(!empty($valor)){
            switch($type) {
                case 'Symfony\Component\Form\Extension\Core\Type\CheckboxType':
                    return 'true';
                default:
                    $tmp = explode('-', $valor);
                    return isset($tmp[0]) ? $tmp[0] : $valor;
            }
        }
        return null;
    }

    static public function getSqlWhere($criterios){
        $sql='';
        if(count($criterios)) {
            $sql=' WHERE ';
            $i=0;
            foreach($criterios as $campo => $valor) {
                $params=explode('.',$campo);
                $is_campo=count(explode('_',$campo))>1;
                if($i) {
                    $sql.=' AND ';
                }
                if(is_array($valor)){
                    if(isset($valor['campo'])){
                        $sql.=$valor['campo']."='".$valor['valor']."'";
                    }
                    else{
                        $sql.=$campo.(count($params)==1 ? ($is_campo ? '' : '.id') : '').' IN ('.implode(',',$valor).')';
                    }
                }
                elseif(in_array($valor,array('true','false'))) {
                    $sql.=$campo." = '".$valor."'";
                }
                else {
                    $sql.=$campo.(count($params)==1 ? ($is_campo ? '' : '.id') : '')." = '".$valor."'";
                }

                $i++;
            }
        }
        return $sql;
    }


    public function getName(){
        return 'ad_perfil_filtro_manager';
    }
}
