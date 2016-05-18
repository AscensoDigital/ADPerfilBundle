<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-06-15
 * Time: 09:33 AM
 */

namespace AscensoDigital\PerfilBundle\Doctrine;


use AscensoDigital\PerfilBundle\Configuration\Configurator;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FiltroManager{

    protected $requestStack;
    protected $configurator;
    protected $filtrosNormalized = array();
    protected $filtroValor = array();
    protected $qb;
    protected $route;

    private $procesado=false;

    public function __construct(RequestStack $requestStack, Configurator $configurator) {
        $this->requestStack = $requestStack;
        $this->configurator = $configurator;
    }

    public function addFiltroValor($filtroName,$value){
        $table_alias=$this->normalizar($filtroName,null,$value);
        $this->filtroValor[$table_alias]=$value;
        return $this;
    }

    public function addNormalizedFiltro($keyField, $filtroNormal) {
        $this->filtrosNormalized[$keyField]=$filtroNormal;
        return $this;
    }

    public function addNormalizedFiltros(array $filtrosAdd){
        $this->filtrosNormalized=$filtrosAdd + $this->filtrosNormalized;
        return $this;
    }

    public function getFiltros($route=null) {
        $request=$this->getRequest();
        $null_request=is_null($request);
        $route= is_null($route) ? (is_null($this->route) ? ($null_request ? '' : $request->attributes->get('_route')) : $this->route) : $route;
        $filtros= $null_request ? array() : $request->get('ad_perfil_filtros',$request->get('filtros_'.$route,$request->getSession()->get('filtros_'.$route,array())));
        if(false===$null_request){
            $request->getSession()->set('filtros_'.$route, $filtros);
        }

        return $filtros;
    }

    public function getFiltrosValor($route=null) {
        if(0 === count($this->filtroValor)){
            $this->procesa($route);
        }
        return $this->filtroValor;
    }

    public function getFiltroValor($key,$route=null) {
        if(0 === count($this->filtroValor)){
            $this->procesa($route);
        }
        if(isset($this->filtroValor[$key])) {
            return $this->filtroValor[$key];
        }
        return null;
    }

    public function getPerfilField() {
        return $this->configurator->getConfiguration('perfil_table_alias').'.id';
    }

    public function getPerfilTableAlias() {
        return $this->configurator->getConfiguration('perfil_table_alias');
    }

    public function getPermisoField() {
        return 'adp_prm.id';
    }

    public function getPermisoTableAlias() {
        return 'adp_prm';
    }

    public function getQueryBuilder(QueryBuilder $qb, $excludes=null, $isNull=false) {
        if(is_null($excludes)) {
            $excludes=array();
        }
        if(false===$this->procesado){
            $this->procesa();
        }
        foreach ($this->filtrosNormalized as $field => $data) {
            if(!in_array($field,$excludes)) {
                $operator=$data['operator'];
                $key=str_replace("-", "_", str_replace(".","_" ,$field));
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
                                $qb->andWhere($qb->expr()->orX($qb->expr()->isNull($field), $qb->expr()->$operator($data['function'].'('.$field.')',$data['function'].'(:valor_'.$key.')')))
                                    ->setParameter(':valor_'.$key, $data['value']);
                            }
                            else {
                                $qb->andWhere($qb->expr()->$operator($data['function'].'('.$field.')', $data['function'].'(:valor_' . $key .')'))
                                    ->setParameter(':valor_' . $key, $data['value']);
                            }
                        }
                        else {
                            if(true===$isNull){
                                $qb->andWhere($qb->expr()->orX($qb->expr()->isNull($field), $qb->expr()->$operator($field,':valor_'. $key)))
                                    ->setParameter(':valor_'.$key, $data['value']);
                            }
                            else {
                                $qb->andWhere($qb->expr()->$operator($field, ':valor_' . $key))
                                    ->setParameter(':valor_' . $key, $data['value']);
                            }
                        }
                }
            }
        }
        return $qb;
    }

    public function setRoute($route) {
        $this->route=$route;
        return $this;
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

    protected function procesa($route = null) {
        $filtros=$this->getFiltros($route);
        $ret=array();
        foreach($filtros as $filName => $data){
            $alias=$this->getAlias($filName);
            if(is_array($alias)){
                $encontrado=false;
                $lastKeyItem="";
                foreach ($alias as $keySubitem => $aliasSubitem) {
                    if(isset($data[$keySubitem])) {
                        $valor=$this->procesaValor($data[$keySubitem], $this->getType($filName));
                        if(!is_null($valor)) {
                            $ret[$aliasSubitem] = $valor;
                            $this->normalizar($filName,$aliasSubitem,$valor);
                        }
                        $encontrado=true;
                    }
                    $lastKeyItem=$keySubitem;
                }
                if(false===$encontrado){
                    $valor=$this->procesaValor($data[$lastKeyItem], $this->getType($filName));
                    if(!is_null($valor)) {
                        $ret[$alias[$lastKeyItem]] = $valor;
                        $this->normalizar($filName,$alias[$lastKeyItem],$valor);
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
        $this->filtroValor=$ret;
        return $this->filtroValor;
    }

    private function normalizar($filtroName, $table_alias, $valor) {
        $filtroConf = $this->configurator->getFiltroConfiguration($filtroName);
        if(is_null($table_alias)){
            if(is_array($filtroConf['table_alias'])){
                throw new \LogicException('No se puede determinar el table_alias del filtro "'.$filtroName.'"');
            }
            else {
                $table_alias=$filtroConf['table_alias'];
            }
        }
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
        return $table_alias;
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
                case 'Symfony\Component\Form\Extension\Core\Type\DateType':
                    return $valor;
                default:
                    $tmp = explode('-', $valor);
                    return isset($tmp[0]) ? $tmp[0] : $valor;
            }
        }
        return null;
    }

    public function getSqlWhere(QueryBuilder $qb, $excludes = null, $isNull=false){
        $query=$this->getQueryBuilder($qb,$excludes,$isNull)->getQuery();
        $sql=$query->getDQL();
        $pos=strpos($sql,'WHERE');
        if($pos === false){
            return '';
        }
        $sql=substr($sql,$pos-1);
        /** @var Parameter $parameter */
        foreach ($query->getParameters() as $parameter) {
            switch ($parameter->getType()){
                case 'integer':
                    $sql=str_replace(':'.$parameter->getName(),$parameter->getValue(),$sql);
                    break;
                case 101:
                case 102:
                    $sql=str_replace(':'.$parameter->getName(),implode(',',$parameter->getValue()),$sql);
                    break;
                default:
                    $sql=str_replace(':'.$parameter->getName(),"'".$parameter->getValue()."'",$sql);
            }
        }
        return $sql;
    }


    public function getName(){
        return 'ad_perfil_filtro_manager';
    }
}
