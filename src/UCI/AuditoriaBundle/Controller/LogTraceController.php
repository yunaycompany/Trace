<?php

namespace UCI\AuditoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UCI\AuditoriaBundle\Entity\LogTrace;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * LogTrace controller.
 *
 */
class LogTraceController extends Controller
{
    /**
     * IP para validar el stacked
     * @var string
     */
    private $ip = '::1';

    /**
     * Listado de todas las trazas del sistema.
     * @return JsonResponse
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $inicio = $this->getRequest()->query->get('page');
        $fin = $this->getRequest()->query->get('limit');
        $usuario = $this->getRequest()->query->get('usuario');
        $fi = $this->getRequest()->query->get('fechaini');
        $ff = $this->getRequest()->query->get('fechafin');
        $ip = $this->getRequest()->query->get('ip');
        $acc = $this->getRequest()->query->get('acc');
        $params = array(
            'usuario' => $usuario,
            'fi' => $fi,
            'ff' => $ff,
            'ip' => $ip,
            'acc' => $acc
        );
        $total = 0;
        $entities = 0;
        if (empty($usuario) && empty($fi) && empty($ff) && empty($ip) && empty($acc)) {
            $result = $em->getRepository('AuditoriaBundle:LogTrace')->findAll();
            $entities = $em->getRepository('AuditoriaBundle:LogTrace')->getPaginateTrazas($params, $inicio, $fin);
            $total = count($result);
        } else {
            $entities = $em->getRepository('AuditoriaBundle:LogTrace')->getPaginateTrazas($params, $inicio, $fin);
            $rest = $em->getRepository('AuditoriaBundle:LogTrace')->getPaginateTrazas($params);
            $total = count($rest);
        }
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['total'] = $total;
        $respuesta['trazas'] = array();
        foreach ($entities as $entity) {
            $data = array(
                'id' => $entity->getId(),
                'fecha' => $entity->getFecha()->format('d-m-Y'),
                'hora' => $entity->getHora()->format('H:i:s'),
                'usuario' => $entity->getUsuario(),
                'ip' => $entity->getIp(),
                'accion' => $entity->getAccion(),
                'detalles' => $entity->getDetalles(),
            );
            array_push($respuesta['trazas'], $data);
        }

        return new JsonResponse($respuesta);
    }

    /**
     * Punto de entrada a la aplicación
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function inicioAction()
    {
        return $this->render('AuditoriaBundle:LogTrace:index.html.twig');
    }

    /**
     * Retorna el listado de IPs
     * @return JsonResponse
     */
    public function ipAction()
    {
        $em = $this->getDoctrine()->getManager();
        $resultado = $em->getRepository('AuditoriaBundle:LogTrace')->getAllIp();
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['datos'] = array();
        foreach ($resultado as $identificador) {
            $data = array(
                'ip' => $identificador['ip']
            );
            array_push($respuesta['datos'], $data);
        }

        return new JsonResponse($respuesta);
    }

    /**
     * Retorna el listado de Usuarios
     * @return JsonResponse
     */
    public function usuariosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AuditoriaBundle:LogTrace')->getUUser();
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['usuarios'] = array();
        foreach ($entities as $entity) {
            $data = array(
                'usuario' => $entity['usuario']
            );
            array_push($respuesta['usuarios'], $data);
        }

        return new JsonResponse($respuesta);
    }

    /**
     *  Retorna las trazas por IP listas para graficar
     * @return JsonResponse
     */
    public function trazaipAction()
    {
        $em = $this->getDoctrine()->getManager();
        $resultado = $em->getRepository('AuditoriaBundle:LogTrace')->getIPTrace();
        $pila = array('ip' => '::1', 'Create' => 0, 'Update' => 0, 'Delete' => 0, 'Autentication' => 0);
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['datos'] = array();
        $total_trazas = count($resultado)-1;
        foreach ($resultado as $valor) {
            if ($total_trazas > 0) {
                $total_trazas = $total_trazas - 1;
			
                if ($this->ip == $valor['ip']) {
                    $pila['ip'] = $valor['ip'];
                    $pila[$valor['accion']] = (int)$valor['total'];
                } else {			
                    array_push($respuesta['datos'], $pila);
                    $pila['ip'] = $valor['ip'];
                    $this->ip = $valor['ip'];
                    $pila['Create'] = 0;
                    $pila['Update'] = 0;
                    $pila['Delete'] = 0;
                    $pila['Autentication'] = 0;
                    $pila[$valor['accion']] = (int)$valor['total'];		
                }					
               
            } else {
                if($this->ip != $valor['ip'])
                {
                    array_push($respuesta['datos'], $pila);
                    $pila = array('ip' => '::1', 'Create' => 0, 'Update' => 0, 'Delete' => 0, 'Autentication' => 0);
                }

                $pila['ip'] = $valor['ip'];
                $pila[$valor['accion']] = (int)$valor['total'];
                array_push($respuesta['datos'], $pila);

            }
        }
        return new JsonResponse($respuesta);
    }
    /**
     * Retorna el listado de todas las trazas por usuarios
     * @return JsonResponse
     */

    public function alltrazaAction()
    {
        $usuario = $this->getRequest()->query->get('usuario');
        $em = $this->getDoctrine()->getManager();
        $resultado = $em->getRepository('AuditoriaBundle:LogTrace')->getTrazasByUser($usuario);
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['datos'] = array();
        foreach ($resultado as $identificador) {
            $data = array(
                'accion' => $identificador['accion'],
                'total' => (int)$identificador['total']
            );
            array_push($respuesta['datos'], $data);
        }

        return new JsonResponse($respuesta);
    }

    /**
     * Crear Archivo de Logs
     * @return File
     */

    public function generarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getRequest()->query->get('user');
        $fi = $this->getRequest()->query->get('fechai');
        $ff = $this->getRequest()->query->get('fechaf');
        $ip = $this->getRequest()->query->get('ip');
        $acc = $this->getRequest()->query->get('acc');
        $params = array(
            'usuario' => $user,
            'fi' => $fi,
            'ff' => $ff,
            'ip' => $ip,
            'acc' => $acc
        );
        $entities = $em->getRepository('AuditoriaBundle:LogTrace')->getPaginateTrazas($params);
        $nombre_fichero = 'Traza_' . date('d-m-Y').'_'.date('H-m-s'). '.log';
        if (empty($entities)) {
            $texto = 'No hay resultados que mostrar';
            file_put_contents($nombre_fichero, $texto, FILE_APPEND | LOCK_EX);
        } else {
            foreach ($entities as $traza) {
                $ip = $traza->getIp();
                $usuario = $traza->getUsuario();
                $fecha = $traza->getFecha()->format('d-m-Y');
                $hora = $traza->getHora()->format('H:i:s');
                $accion = $traza->getAccion();
                $detalles = $traza->getDetalles();
                $string = "[ $fecha - $hora ] ---- Usuario: $usuario, IP: $ip, Acción: $accion, Detalles: $detalles .\n";
                file_put_contents($nombre_fichero, $string, FILE_APPEND | LOCK_EX);
            }
        }
        if (file_exists($nombre_fichero)) {
            header('Content-Description: File Transfer');
           // header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($nombre_fichero));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombre_fichero));
            ob_clean();
            flush();
            readfile($nombre_fichero);
            unlink($nombre_fichero);
            exit;
        }
    }
    public function getTempAction()
    {
        $em = $this->getDoctrine()->getManager();

        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['datos'] = array();

        $horas= array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00','00:00');
        for($i=0; $i<count($horas)-1;$i++){

           $resultado= $em->getRepository('AuditoriaBundle:LogTrace')->getAccionByHora($horas[$i],$horas[$i+1]);
            $data = array(
                'horario' => $horas[$i].'-'.$horas[$i+1],
                'total' => (int)$resultado[0]['Total']
             );
            array_push($respuesta['datos'],$data);
        }
        return new JsonResponse($respuesta);
    }
    public function getAllAccionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getRequest()->query->get('user');
        $fi = $this->getRequest()->query->get('fi');
        $ff = $this->getRequest()->query->get('ff');
        $ip = $this->getRequest()->query->get('ip');
        $params = array(
            'usuario' => $user,
            'fi' => $fi,
            'ff' => $ff,
            'ip' => $ip
        );
        $respuesta = array();
        $respuesta['success'] = true;
        $respuesta['datos'] = array();

        $horas= array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00','00:00');
        for($i=0; $i<count($horas)-1;$i++){

            $resultado= $em->getRepository('AuditoriaBundle:LogTrace')->getTempAccionByHora($params,$horas[$i],$horas[$i+1]);
            $pila = array('horario' => $horas[$i].'-'.$horas[$i+1],'Create' => 0, 'Update' => 0, 'Delete' => 0, 'Autentication' => 0);

            foreach($resultado as $valor){
                    $pila[$valor['accion']] = $pila[$valor['accion']]+1;
            }
            array_push($respuesta['datos'],$pila);
        }
        return new JsonResponse($respuesta);
    }

}
