<?php

namespace UCI\AuditoriaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;

/**
 * LogTraceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogTraceRepository extends EntityRepository {

    public function getPaginateTrazas($params, $inicio= null, $cantidad= null) {
        $em = $this->getEntityManager();
        $us = $params['usuario'];
        $fi= $params['fi'];
        $ff= $params['ff'];
        $ip= $params['ip'];
        $acc= $params['acc'];
        $qb = $em->createQueryBuilder();
        $qb->add('select','t')
           ->add('from','UCI\AuditoriaBundle\Entity\LogTrace t');
        if(!empty($us)){
            $qb->add('where',$qb->expr()->orX($qb->expr()->eq('t.usuario','?1')));
            $qb->setParameter(1,$us);
        }
        if(!empty($fi)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->gte('t.fecha','?2')));
            $qb->setParameter(2,$fi);
        }
        if(!empty($ff)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->lte('t.fecha','?3')));
            $qb->setParameter(3,$ff);
        }
        if(!empty($ip)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->eq('t.ip','?4')));
            $qb->setParameter(4,$ip);
        }
        if(!empty($acc)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->eq('t.accion','?5')));
            $qb->setParameter(5,$acc);
        }
        if(!empty($inicio) && !empty($cantidad)){
            $qb->setFirstResult(($inicio*$cantidad)-$cantidad);
            $qb->setMaxResults($cantidad);
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }
/**
    public function getTrazasMes($fechai, $fechaf) {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT t.accion, t.fecha,  COUNT(t.accion) AS total FROM UCI\AuditoriaBundle\Entity\LogTrace t GROUP BY t.fecha, t.accion');
        //$query->setParameter(1, $fechai);
       // $query->setParameter(2, $fechaf);           
        return $query->getResult();
    }*/
    public function getTrazasByUser($user = null){
        $em = $this->getEntityManager();
        if($user == null){
            $query = $em->createQuery('SELECT t.accion,  COUNT(t.accion) AS total FROM UCI\AuditoriaBundle\Entity\LogTrace t GROUP BY t.accion');
        }else{
            $query = $em->createQuery('SELECT t.accion,  COUNT(t.accion) AS total FROM UCI\AuditoriaBundle\Entity\LogTrace t WHERE t.usuario = ?1 GROUP BY t.accion');
            $query->setParameter(1, $user);            
        }
        return $query->getResult();
    }
     public function getUUser(){
         $em = $this->getEntityManager();
         $query = $em->createQuery('SELECT DISTINCT t.usuario FROM UCI\AuditoriaBundle\Entity\LogTrace t');
         return $query->getResult();         
     }

    public function getIPTrace($ip = null){
        $em = $this->getEntityManager();
        if($ip == null){
             $query = $em->createQuery("SELECT t.ip, t.accion, COUNT(t.accion) AS total FROM UCI\AuditoriaBundle\Entity\LogTrace t GROUP BY t.ip, t.accion ORDER BY t.ip ASC");
        }else{
            $query = $em->createQuery("SELECT t.ip, t.accion, COUNT(t.accion) AS total FROM UCI\AuditoriaBundle\Entity\LogTrace t WHERE t.ip = ?1 GROUP BY t.ip, t.accion ORDER BY t.ip ASC");
            $query->setParameter(1, $ip);
        }
        /*var_dump($query->getResult());
        die();*/
            return $query->getResult();
    }
    public function getAllIp(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT DISTINCT t.ip FROM UCI\AuditoriaBundle\Entity\LogTrace t');
        return $query->getResult();
    }
    public function getAccionByHora($horai, $horaf)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(t.accion) AS Total FROM UCI\AuditoriaBundle\Entity\LogTrace t WHERE t.hora >= ?1 AND t.hora< ?2');
        $query->setParameter(1, $horai);
        $query->setParameter(2, $horaf);
        return $query->getResult();
    }
    public function getTempAccionByHora($params, $horai, $horaf)
    {
        $em = $this->getEntityManager();
        $us = $params['usuario'];
        $fi = $params['fi'];
        $ff = $params['ff'];
        $ip = $params['ip'];
        $qb = $em->createQueryBuilder();
        $qb->add('select','t.accion')
            ->add('from','UCI\AuditoriaBundle\Entity\LogTrace t');
        if(!empty($us)){
            $qb->add('where',$qb->expr()->orX($qb->expr()->eq('t.usuario','?1')));
            $qb->setParameter(1,$us);
        }
        if(!empty($fi)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->gte('t.fecha','?2')));
            $qb->setParameter(2,$fi);
        }
        if(!empty($ff)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->lte('t.fecha','?3')));
            $qb->setParameter(3,$ff);
        }
        if(!empty($horai)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->gte('t.hora','?4')));
            $qb->setParameter(4,$horai);
        }
        if(!empty($horaf)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->lte('t.hora','?5')));
            $qb->setParameter(5,$horaf);
        }
        if(!empty($ip)){
            $qb->andWhere($qb->expr()->orX($qb->expr()->eq('t.ip','?6')));
            $qb->setParameter(6,$ip);
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
