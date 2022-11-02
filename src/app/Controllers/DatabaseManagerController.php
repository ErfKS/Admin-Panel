<?php

namespace erfan_kateb_saber\admin_panel\app\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use mysql_xdevapi\Exception;

class DatabaseManagerController extends Controller
{
    /*
     * table name (string)
     * have id (boolean)
     * have time stamps (boolean)
     *
     * column [int] name (string)
     * column [int] length (int)
     * column [int] type (string)
     * column [int] nullable (boolean)
     * column [int] auto increment (boolean)
     *
     * */
    public function createNewTable(Request $request)
    {

        //validation
        $this->validate($request, [
            'table_name' => ["required"],
        ]);


        for ($i=1;$i<(int)$request->get('tableCount')+1;$i++){
            if($request->get("column_".$i."_name") !== null) {
                $this->validate($request, [
                    "column_" . $i . "_length" => ["required", "integer"],
                ]);
            }
        }

        Schema::create($request->get('table_name'), function (Blueprint $table) use ($request) {
            if($request->get('have_id') === "on"){
                $table->id();
            }

            for ($i=1;$i<(int)$request->get('tableCount')+1;$i++){

                if($request->get("column_".$i."_name")===null || $request->get("column_".$i."_name")==="")
                    continue;

                switch ($request->get("column_".$i."_type")){
                    case 'INT':
                        $table->integer($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'CHAR':
                        $table->char($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'VARCHAR':
                        $table->string($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'DATE':
                        $table->date($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'TINYINT':
                        $table->tinyInteger($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'SMALLINT':
                        $table->smallInteger($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MEDIUMINT':
                        $table->mediumInteger($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'BIGINT':
                        $table->bigInteger($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'DECIMAL':
                        $table->decimal($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'FLOAT':
                        $table->float($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    /*case 'BIT':
                        $table->BIT($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;*/
                    case 'DOUBLE':
                        $table->double($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'BINARY':
                        $table->binary($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    /*case 'VARBINARY':
                        $table->VARBINARY($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'TINYBLOB':
                        $table->TINYBLOB($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'BLOB':
                        $table->BLOB($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MEDIUMBLOB':
                        $table->MEDIUMBLOB($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'LONGBLOB':
                        $table->LONGBLOB($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;*/
                    case 'TINYTEXT':
                        $table->tinyText($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'TEXT':
                        $table->text($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MEDIUMTEXT':
                        $table->mediumText($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'LONGTEXT':
                        $table->longText($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'ENUM':
                        $table->enum($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'SET':
                        $table->set($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'TIME':
                        $table->time($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'DATETIME':
                        $table->dateTime($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'TIMESTAMP':
                        $table->timestamp($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'YEAR':
                        $table->year($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'GEOMETRY':
                        $table->geometry($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'POINT':
                        $table->point($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'LINESTRING':
                        $table->lineString($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'POLYGON':
                        $table->polygon($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'GEOMETRYCOLLECTION':
                        $table->geometryCollection($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MULTILINESTRING':
                        $table->multiLineString($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MULTIPOINT':
                        $table->multiPoint($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'MULTIPOLYGON':
                        $table->multiPolygon($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    case 'JSON':
                        $table->json($request->get("column_".$i."_name"),$request->get("column_".$i."_length"));
                        break;
                    default:
                        $type = $request->get("column_".$i."_type");
                        throw new \ErrorException("this type '$type' is not exist");
                }
            }

            if($request->get('have_time_stamps') === "on"){
                $table->timestamps();
            }
        });
    }
}
