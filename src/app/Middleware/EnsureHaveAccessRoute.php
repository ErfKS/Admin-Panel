<?php

namespace erfan_kateb_saber\admin_panel\app\Middleware;

use erfan_kateb_saber\admin_panel\app\Controllers\admin_panelController;
use erfan_kateb_saber\admin_panel\app\Extensions\Xml\XML_Manager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHaveAccessRoute
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get xml data
        $data = XML_Manager::xmlToArray('/admin_panel/db_route_access.xml', ["route", "prefix"]);


        if(!$data){ // if exist route access database
            // create route access database
            (new admin_panelController)->freshDatabase('part');
        }

        // Get current request path
        $currentPath = $request->path();

        // Check Exeption
        $currentPathComponent = explode('/', $currentPath);
        if($currentPathComponent[0] === "permission"){
            return $next ($request);
        }


        // Check auto path
        if($this->checkPath($currentPath, $data['path_access']) == 'off'){
            return abort(403);
        }

        // Check manual path
        if($this->checkPath($currentPath, $data['manual_path_access']) == 'off'){
            return abort(403);
        }

        // Check auto pref
        if($this->checkPrefix($currentPath, $data['pref_path_access']) == 'off'){
            return abort(403);
        }

        // Check manual pref
        if($this->checkPrefix($currentPath, $data['manual_pref_path_access']) == 'off'){
            return abort(403);
        }

        return $next ($request);
    }

    /**
     *
     * combine Array to String
     *
     * @param array $array
     * @param int $from
     * @param int $to
     * @return string
     */
    private function combineArray(array $array, int $from, int $to):string {
        $tempString = '';
        for ($i=$from;$i<$to;$i++){
            $tempString .= $array[$i];
            if($i !== $to){
                $tempString .= '/';
            }
        }
        return $tempString;
    }

    /**
     * @param string $currentPath
     * @param array $paths_check
     * @return mixed
     */
    private function checkPath(string $currentPath, array $paths_check): string
    {
        return $this->compoundPathWithArray($currentPath, $paths_check,true);
    }

    /**
     * @param string $currentPath
     * @param array $prefs_check
     * @return mixed
     */
    private function checkPrefix(string $currentPath, array $prefs_check): string
    {
        return $this->compoundPathWithArray($currentPath, $prefs_check);
    }

    /**
     * @param string $currentPath
     * @param array $arrayValue
     * @param bool $must_equal
     * @return string
     */
    public function compoundPathWithArray(string $currentPath, array $arrayValue, bool $must_equal = false)
    {
        $currentPathComponent = explode('/', $currentPath);
        foreach ($arrayValue as $pref => $status) {
            $pathComponent = explode('/', $pref);

            if ($must_equal && count($pathComponent) !== count($currentPathComponent)) {
                continue;
            }

            if ($currentPathComponent === $pathComponent) {
                return $status;
            }

            for ($i = count($arrayValue) - 1; $i > 0; $i--) {
                if ($this->combineArray($pathComponent, 0, $i) === $this->combineArray($currentPathComponent, 0, $i)) {
                    return $status;
                }
            }
        }
        return 'null';
    }
}

