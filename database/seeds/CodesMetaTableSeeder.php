<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodesMetaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER SEQUENCE codes_meta_id_seq RESTART WITH 1');
        $data = $this->mergeMeta();
        DB::table('codes_meta')->insert($data); 
    }

    public function mergeMeta()
    {
    	$values = [];
    	$data  	= [
    		'rol'    =>	$this->rolesMeta  (),
            'lesson' =>	$this->lessonsMeta(),
            'status' => $this->statusMeta (),
            'level'  => $this->levelsMeta (),
    	];
    	foreach ($data as $type => $options) {
    		foreach ($options as $key => $value) {
    			array_push($values, [
    				'type'	=>	$type,
    				'key'	=>	$key,
    				'value'	=>	$value
    			]);	
    		}
    	}
    	return $values;
    }

    public function rolesMeta()
    {
    	return [
    		'AD' => 'Admin',
            'SC' => 'Scheduler',
            'TE' => 'Teacher',
            'ST' => 'Student',    	
    	];
    }

    public function lessonsMeta()
    {
    	return [
    		'GR' => 'Gramatical',
    		'CN' => 'Conversational'
    	];
    }    
    
    public function levelsMeta()
    {
    	return [
    		'BAS' => 'Basic',
            'MED' => 'Medium',
    		'ADV' => 'Advanced',            
    	];
    }

    public function statusMeta()
    {
        return [
            'ACT'=>'Active'  ,
            'PEN'=>'Pending' ,
            'CAN'=>'Canceled',
            'FIN'=>'Finished',
            'BLO'=>'Blocked' 
        ];
    }

}

