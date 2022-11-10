<?php

namespace erfan_kateb_saber\admin_panel\app\Controllers;

use App\Http\Controllers\Controller;
use erfan_kateb_saber\admin_panel\app\Extensions\Xml\XML_Manager;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class admin_panelController extends Controller
{

    function navLinks(): array
    {
        return [
            'Routes' => [
                'type' => 'Dropdown',
                'buttons' => [
                    'Auto Routes' => [
                        'href' => route('admin_panel.getList', ['routes','all']),
                    ],
                    'Prefix Routes' => [
                        'href' => route('admin_panel.getList', ['routes','prefix']),
                    ],
                    'Manual Routes List' => [
                        'href' => route('admin_panel.getList', ['routes','manual-all']),
                    ],
                    'Manual Prefix Routes List' => [
                        'href' => route('admin_panel.getList', ['routes','manual-prefix']),
                    ]
                ]
            ],
            'Databases' => [
                'type' => 'Button',
                'href' => route('admin_panel.getList', ['databases','tables']),
                'btn-color-class' => 'btn-secondary'
            ],
            'Fresh Database' => [
                'type' => 'Button',
                'href' => route('admin_panel.freshDatabase', 'part'),
                'text-color-class' => 'text-danger',
                'onClick' => 'Scroll_manager.saveScroll();',
            ],
            'Optimize XML Database' => [
                'type' => 'Button',
                'href' => route('admin_panel.updateDatabase'),
                'text-color-class' => 'text-warning',
                'onClick' => 'Scroll_manager.saveScroll();',
            ]
        ];
    }

    function arrayToXml($array, $rootElement = '<admin_panels/>', $xml = null)
    {
        return XML_Manager::arrayToXml($array, '/admin_panel/db_route_access.xml', $rootElement, $xml);
    }

    private function xmlToArray($ignore_negativeFilter = false)
    {
        return XML_Manager::xmlToArray('admin_panel/db_route_access.xml', ['route', 'prefix'], $ignore_negativeFilter);
    }

    public function index(Request $request)
    {

        $buttons = [
            'Get Auto Routes' => [
                'route' => $this->navLinks()['Routes']['buttons']['Auto Routes']['href'],
                'btn-color-class' => 'btn-secondary'
            ],
            'Get Prefix Routes' => [
                'route' => $this->navLinks()['Routes']['buttons']['Prefix Routes']['href'],
                'btn-color-class' => 'btn-secondary'
            ],

            'Get All DB Tables' => [
                'route' => $this->navLinks()['Databases']['href'],
                'btn-color-class' => 'btn-secondary'
            ],
            'Fresh Database' => [
                'route' => route('admin_panel.freshDatabase', 'part'),
                'btn-color-class' => 'btn-danger'
            ],
            'Optimize XML Database' => [
                'route' => route('admin_panel.updateDatabase'),
                'btn-color-class' => 'btn-warning'
            ],
        ];

        $data = [
            'buttons' => $buttons,
            'navLinks' => $this->navLinks()
        ];
        return view('main::index', $data);
    }

    private function getPrefixList(): array
    {
        $all_routes = Route::getRoutes();
        $prefix_routes = array();
        foreach ($all_routes as $route) {
            $prefix = $route->getPrefix();
            if ($prefix) {
                if (isset($prefix_routes[$prefix])) {
                    $prefix_routes[$prefix]['count']++;
                } else {
                    $prefix_routes[$prefix] = [
                        'pref' => $prefix,
                        'count' => 1,
                        'status' => isset($this->xmlToArray()['pref_path_access'][$prefix]) ?
                            $this->xmlToArray()['pref_path_access'][$prefix] : "on"
                    ];
                }

            }
        }
        return $prefix_routes;
    }

    public function addManualPrefixRoute(Request $request)
    {
        //validation
        $validate_data = $this->validate($request, [
            'pref' => ["required"],
            'status' => [""]
        ]);

        //check route counts
        $data = $this->xmlToArray();

        //set data array
        $data['manual_pref_path_access'][$validate_data['pref']] = $validate_data['status'] ?? "off";

        //store data
        $this->arrayToXml($data);

        return back();
    }

    private function isPrefixRoute($route, $prefix)
    {
        $prefix_properties = explode('/', $prefix);
        $route_properties = explode('/', $route);

        for ($i = 0; $i < count($prefix_properties); $i++) {
            if ($prefix_properties[$i] === $route_properties[$i]) {
                return true;
            }
        }
        return false;
    }

    private function checkManualPrefixCount(array $xml_value_paths,string $xml_value_pref) : int{
        $routes = $xml_value_paths;
        $prefix = $xml_value_pref;
        $prefixCount = 0;
        foreach ($routes as $route=>$status){
            if($this->isPrefixRoute($route,$prefix)){
                $prefixCount++;
            }
        }

        return $prefixCount;
    }

    public function addManualPathRoute(Request $request)
    {
        $validate_data = $this->validate($request, [
            'path' => ['required'],
            'status' => ['']
        ]);

        $data = $this->xmlToArray();

        $data['manual_path_access'][$validate_data['path']] = $validate_data['status'] ?? "off";

        //store data
        $this->arrayToXml($data);

        return back();
    }

    private function getPathadmin_panel()
    {
        $routes = Route::getRoutes();
        $pathadmin_panel = array();
        foreach ($routes as $route) {

            $pathadmin_panel[$route->uri()] = [
                'path' => $route->uri(),
                'name' => $route->getName(),
                'pref' => $route->getPrefix(),
                'method' => $route->methods()[0],
                'controller' => isset($route->getAction()['controller']) ? $route->getAction()['controller'] : "",
                'status' => isset($this->xmlToArray()['path_access'][$route->uri()]) ?
                    $this->xmlToArray()['path_access'][$route->uri()] : "on"

            ];
        }
        return $pathadmin_panel;
    }

    public function getList(Request $request, $part,$list)
    {
        $mode = $list;
        $form_properties = null;
        $table_list = array();
        switch ($part)
        {
            case 'routes':
                switch ($list) {
                    case 'all':
                        $table_list = $this->getPathadmin_panel();
                        $title = 'Auto Routes';
                        $head = [
                            'Route',
                            'name',
                            'pref',
                            'Method',
                            'Controller',
                            'Status'
                        ];
                        break;
                    case 'prefix':
                        $table_list = $this->getPrefixList();
                        $title = 'Auto Prefix Routes';
                        $head = [
                            'prefix',
                            'count',
                            'Status'
                        ];
                        break;
                    case 'manual-all':
                        $xml_manual_path = $this->xmlToArray()['manual_path_access'];
                        $table_list = array();

                        foreach ($xml_manual_path as $path => $status){
                            $table_list[$path] = [
                                'path' => $path,
                                'pref' => $this->getPrefixManualPath($path,$this->xmlToArray()['manual_pref_path_access']),
                                'status' => $status
                            ];
                        }

                        $title = "Manual Routes";
                        $head = [
                            'Route',
                            'pref',
                            'Status'
                        ];
                        $form_properties = [
                            'method' => 'post',
                            'csrf' => true,
                            'action' => route('admin_panel.addManualPathRoute'),
                            'inputs' => [
                                'path' => [
                                    'required' => true,
                                    'type' => 'text',
                                    'label' => 'path',
                                    'description' => 'eg: admin_panel/getList/manual-all',
                                    'name' => 'path'
                                ],
                                'status' => [
                                    'label' => 'status',
                                    'type' => 'checkbox',
                                    'name' => 'status'
                                ]
                            ],
                            'submit_button' => [
                                'text' => 'insert',
                                'color' => 'primary',
                            ]
                        ];
                        break;
                    case 'manual-prefix':
                        $xml_manual_pref=$this->xmlToArray()['manual_pref_path_access'];
                        $xml_manual_path = $this->xmlToArray()['manual_path_access'];
                        foreach ($xml_manual_pref as $pref =>$status){
                            $table_list[$pref] = [
                                'pref' => $pref,
                                'count' => $this->checkManualPrefixCount($xml_manual_path,$pref),
                                'status' => $status
                            ];
                        }
                        $title = 'Manual Prefix Routes';
                        $head = [
                            'prefix',
                            'count',
                            'Status'
                        ];
                        $form_properties = [
                            'method' => 'post',
                            'csrf' => true,
                            'action' => route('admin_panel.addManualPrefixRoute'),
                            'inputs' => [
                                'prefix' => [
                                    'label' => 'prefix',
                                    'required' => true,
                                    'type' => 'text',
                                    'description' => 'eg: admin_panel/getList/manual-all',
                                    'name' => 'pref'
                                ],
                                'status' => [
                                    'label' => 'status',
                                    'type' => 'checkbox',
                                    'name' => 'status'
                                ]
                            ],
                            'submit_button' => [
                                'text' => 'insert',
                                'color' => 'primary'
                            ]
                        ];
                        break;
                    default:
                        $table_list = $this->xmlToArray()['path_access'];
                        $title = "Not found<hr>Auto Routes";
                        $head = [
                            'Route',
                            'name',
                            'pref',
                            'Method',
                            'Controller',
                            'Status'
                        ];
                        $mode = 'all';
                        break;
                }
                break;
            case 'databases':
                if($list === 'tables'){
                    $table_list = array();
                    try {
                        foreach (DB::select('SHOW TABLES') as $table) {
                            $tableName = $table->Tables_in_mapeduir_site;

                            $table_list[$tableName] = [
                                'table name' => $tableName,
                                'count' => count(DB::table($tableName)->get())
                            ];
                        }

                        $title = 'All Tables';

                    } catch (QueryException $ex){
                        $title = 'Query Exception';
                        $mode = 'error';
                        $table_list = [1];
                        $message = $ex->getMessage();
                    }

                    $head = [
                        'Table Name',
                        'Rows'
                    ];

                    $form_properties = [
                        'method' => 'post',
                        'csrf' => true,
                        'action' => route('admin_panel.createNewTable'),
                        'inputs' => [
                            'table name' => [
                                'required' => true,
                                'type' => 'text',
                                'label' => 'table name',
                                'name' => 'table_name'
                            ],
                            'have id' => [
                                'type' => 'checkbox',
                                'label' => 'have id',
                                'name' => 'have_id'
                            ],
                            'hr'=>['type'=>'hr'],
                            'List Box'=>[
                                'type'=>'listBox',
                                'text'=> 'columns',
                                'create_item_button' =>[
                                    'text'=>'add',
                                    'type' => 'button',
                                    'color' => 'primary'
                                ],
                                'item_count' => 1,
                                'one_item'=> [
                                    'type' => 'row',
                                    'text' => 'column __number__',
                                    'inputs'=>[
                                        'column name' => [
                                            'required' => true,
                                            'type' => 'text',
                                            'label'=>'name',
                                            'name' => 'column___number___name'
                                        ],
                                        'column length' => [
                                            'required' => true,
                                            'type' => 'number',
                                            'label'=> 'length',
                                            'name' => 'column___number___length',
                                            'default'=>0
                                        ],
                                        'column type' => [
                                            'type' => 'dropdown',
                                            'label' => 'type',
                                            'name' => 'column___number___type',
                                            'options' => [
                                                'INT'=>'INT',
                                                'CHAR'=>'CHAR',
                                                'VARCHAR'=>'VARCHAR',
                                                'DATE'=>'DATE',
                                                'TINYINT'=>'TINYINT',
                                                'SMALLINT'=>'SMALLINT',
                                                'MEDIUMINT'=>'MEDIUMINT',
                                                'BIGINT'=>'BIGINT',
                                                'DECIMAL'=>'DECIMAL',
                                                'FLOAT'=>'FLOAT',
                                                'BIT'=>'BIT',
                                                'DOUBLE'=>'DOUBLE',
                                                'BINARY'=>'BINARY',
                                                'VARBINARY'=>'VARBINARY',
                                                'TINYBLOB'=>'TINYBLOB',
                                                'BLOB'=>'BLOB',
                                                'MEDIUMBLOB'=>'MEDIUMBLOB',
                                                'LONGBLOB'=>'LONGBLOB',
                                                'TINYTEXT'=>'TINYTEXT',
                                                'TEXT'=>'TEXT',
                                                'MEDIUMTEXT'=>'MEDIUMTEXT',
                                                'LONGTEXT'=>'LONGTEXT',
                                                'ENUM'=>'ENUM',
                                                'SET'=>'SET',
                                                'TIME'=>'TIME',
                                                'DATETIME'=>'DATETIME',
                                                'TIMESTAMP'=>'TIMESTAMP',
                                                'YEAR'=>'YEAR',
                                                'GEOMETRY'=>'GEOMETRY',
                                                'POINT'=>'POINT',
                                                'LINESTRING'=>'LINESTRING',
                                                'POLYGON'=>'POLYGON',
                                                'GEOMETRYCOLLECTION'=>'GEOMETRYCOLLECTION',
                                                'MULTILINESTRING'=>'MULTILINESTRING',
                                                'MULTIPOINT'=>'MULTIPOINT',
                                                'MULTIPOLYGON'=>'MULTIPOLYGON',
                                                'JSON'=>'JSON'
                                            ]
                                        ],
                                        'column nullable' => [
                                            'type' => 'checkbox',
                                            'label'=> 'nullable',
                                            'name' => 'column___number___nullable'
                                        ],
                                        'have time increment' => [
                                            'type' => 'checkbox',
                                            'label'=> 'have time column',
                                            'name' => 'have_time_column___number__'
                                        ],
                                        'column auto increment' => [
                                            'type' => 'checkbox',
                                            'label'=> 'auto increment column',
                                            'name' => 'auto_increment_column___number__'
                                        ],
                                        'delete'=>[
                                            'type'=>'button',
                                            'name'=>'delete_element___number__',
                                            'onClick'=>"DeleteItem(__number__)",
                                            'text'=>'delete',
                                            'color' => 'danger'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'submit_button' => [
                            'text' => 'create',
                            'color' => 'primary',
                            'onClick'=>'Scroll_manager.saveScroll();'
                        ]
                    ];
                } else if(str_contains($list,'table-')) {
                    $tableName = str_replace('table-','',$list);
                    $head = DB::getSchemaBuilder()->getColumnListing($tableName);
                    $table_list = DB::table($tableName)->get()->all();
                    $title = "table: $tableName";
                } else {
                    return abort(404);
                }
                break;
        }



        $navLinks = [
            'Menu' => [
                'type' => 'Button',
                'href' => route('admin_panel.index'),
            ],
            'Routes' => $this->navLinks()['Routes'],
            'Databases'=>$this->navLinks()['Databases'],
            'Save all' => [
                'type' => 'Button',
                'onClick' => 'Scroll_manager.saveScroll();goEditTotalRoute(\'' . $mode . '\');',
                'text-color-class' => 'text-info',
            ],
            'Optimize XML Database' => $this->navLinks()['Optimize XML Database'],
            'Fresh Database' => $this->navLinks()['Fresh Database'],
        ];

        $data = [
            'title' => $title,
            'list' => $table_list,
            'head' => $head,
            'mode' => $mode,
            'part' => $part,
            'navLinks' => $navLinks,
            'form_properties' => $form_properties,
            'error_message' => $message??null
        ];

        return view('main::routeList', $data);
    }

    public function editTotalValue(Request $request)
    {

        //Get request data
        $post_data = [
            'data' => json_decode($request->get('data'), true),
            'mode' => $request->get('mode')
        ];

        //initialize functions
        function getMainPath($post_data,$oneData){
            return  ($post_data['mode'] == "prefix" || $post_data['mode'] == "manual-prefix") ?
                $oneData['pref'] :
                $oneData['path'];
        }
        //Check database part
        $database_part = $this->getXmlPart($post_data['mode']);

        //get database XML to database array
        $old_data = $this->xmlToArray();
        //create optimized data
        $optimizedData = array();
        foreach ($post_data['data'] as $oneData) {
            if ($oneData['status'] === "off") {
                $optimizedData[getMainPath($post_data,$oneData)] = $oneData['status'];
            } else if($database_part !== 'manual_path_access' && $database_part !== 'manual_pref_path_access') {
                unset($old_data[getMainPath($post_data,$oneData)]);
            } else {
                $optimizedData[getMainPath($post_data,$oneData)] = $oneData['status'];
            }
        }

        // edite database array with $post_data
        $old_data[$database_part]=$optimizedData;

        // store database array to database XML
        $this->arrayToXml($old_data);

        // redirect to previous view page
        return back()->with('modal', [
            'title' => 'result',
            'text' => 'the database Edit was successful.',
            'isShow' => true
        ]);
    }

    public function editStatus(Request $request, $address)
    {
        $data = $this->xmlToArray();
        $address = str_replace("Ocolad_Baz", "{", $address);
        $address = str_replace("Ocolad_Baste", "}", $address);
        $address = str_replace("slash", "/", $address);

        switch ($request->get('mode')) {
            case 'all':
                $data["path_access"][$address]['status'] = $request->get('status') == "on" ? "on" : "off";
                break;
            case 'prefix':
                $data["pref_path_access"][$address]['status'] = $request->get('status') == "on" ? "on" : "off";
                break;
        }
        $this->arrayToXml($data);
        return back()->with('modal', [
            'title' => 'result',
            'text' => 'the database Edit was successful.',
            'isShow' => true
        ]);
    }

    public function freshDatabase($freshMode)
    {
        if ($freshMode == 'all') {
            $this->arrayToXml($this->freshDatabase_getArray(false, false), '<admin_panels/>');
        } else {
            $this->arrayToXml($this->freshDatabase_getArray(), '<admin_panels/>');
        }
        return back()->with('modal', [
            'title' => 'result',
            'text' => 'the database Fresh was successful.',
            'isShow' => true
        ]);
    }

    private function freshDatabase_getArray($isXmlFilter = false, $keepManualData = true)
    {
        if ($keepManualData) {
            $manual_path_access = $this->xmlToArray()['manual_path_access']??null;
            $manual_pref_path_access = $this->xmlToArray()['manual_pref_path_access']??null;
        }
        $data = [
            'path_access' => array(),
            'manual_path_access' => $manual_path_access,
            'pref_path_access' => array(),
            'manual_pref_path_access' => $manual_pref_path_access
        ];
        return $data;
    }

    //Optimize XML Database
    public function updateDatabase()
    {
        $xmlValue = $this->xmlToArray();
        $routes = $this->getPathadmin_panel();
        $prefix = $this->getPrefixList();

        function optimizeXmlPart($part_name, &$xmlValue, $value)
        {
            foreach ($xmlValue[$part_name] as $key => $value) {
                if (!array_key_exists($key, $xmlValue)) {
                    unset($xmlValue[$part_name][$key]);
                }
            }
        }

        optimizeXmlPart('path_access', $xmlValue, $routes);
        optimizeXmlPart('path_access', $xmlValue, $prefix);

        return back()->with('modal', [
            'title' => 'result',
            'text' => 'the database Update was successful.',
            'isShow' => true
        ]);
    }

    function updateManualValues($new)
    {
        $routes = $new['manual_path_access'];
        $prefixes = $new['manual_pref_path_access'];
        foreach ($prefixes as $prefixName => $prefix) {
            $prefixCount = 0;
            foreach ($routes as $routeName => $route) {
                if ($this->isPrefixRoute($route['path'], $prefix['pref'])) {
                    $new['manual_path_access'][$routeName]['pref'] = $prefix['pref'];
                    $prefixCount++;
                    $new['manual_pref_path_access'][$prefixName]['count'] = $prefixCount;
                }
            }
        }
        return $new;
    }

    public function dropRecord(Request $request){
        //Get request data
        $post_data = [
            'data' => json_decode($request->get('data'), true),
            'mode' => $request->get('mode')
        ];

        //Get Database
        $xmlValue = $this->xmlToArray();

        //Delete Record
        unset($xmlValue[$this->getXmlPart($post_data['mode'])][$post_data['data']]);

        //Save Database
        $this->arrayToXml($xmlValue);

        // redirect to previous view page
        return back()->with('modal', [
            'title' => 'result',
            'text' => 'dropManualPath'  .'was successful.',
            'isShow' => true
        ]);
    }


    /**
     * @param $path
     * @param $prefixes
     * @return string
     */
    private function getPrefixManualPath($path,$prefixes): string
    {
        $prefix = '';
        foreach ($prefixes as $key => $status) {
            if ($this->isPrefixRoute($path, $key)) {
                $prefix = $key;
            }
        }
        return $prefix;
    }

    /**
     * @param $mode
     * @return string
     */
    public function getXmlPart($mode): string
    {
        switch ($mode) {
            case "prefix":
                return 'pref_path_access';
            case "all":
                return 'path_access';
            case "manual-all":
                return 'manual_path_access';
            case "manual-prefix":
                return 'manual_pref_path_access';
        }
        return 'null';
    }


    public function getDatabase(){

    }
}
