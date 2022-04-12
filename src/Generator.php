<?php
namespace Agregalel\Lturtle\Console;

class Generator {
    private $configPaths = [];
    private $styleType = '';

    public function __construct() {
        $config = new Config();
        $this->configPaths = $config->getPaths();
        $this->styleType = $config->getStyleType();
    }

    public function generate($generate, $name)
    {
        switch($generate)
        {
            case 'service':
                return $this->generateService($name);
            case 'component':
                return $this->generateComponent($name);
            case 'store':
                return $this->generateStore($name);
            case 'view':
                return $this->generateView($name);
            default:
                throw new \Exception('Invalid generate type');
        }
    }

    private function generateService($name)
    {
        $path = $this->configPaths['service_path'];
        $file = fopen($path.'/'.$name.'Service.js', 'w');
        $nameMinus = strtolower($name);
        fwrite($file, "
import axios from 'axios'

export const get{$name}s = async () => {
    try {
        const response = await axios.get('/api/{$nameMinus}s')
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const get{$name}byId = async (id) => {
    try {
        const response = await axios.get('/api/{$nameMinus}s/' + id)
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const edit{$name} = async (id, {$nameMinus}) => {
    try {
        const response = await axios.put('/api/{$nameMinus}s/' + id, {$nameMinus})
        return response.data
    } catch (error) {
        console.log(error)
    }
}

export const delete{$name} = async (id) => {
    try {
        const response = await axios.delete('/api/{$nameMinus}s/' + id)
        return response.data
    } catch (error) {
        console.log(error)
    }
}
        ");
        fclose($file);
        return "Generated \n {$path}/{$name}Service.js";
    }

    private function generateComponent($name)
    {
        $path = $this->configPaths['component_path'];
        $file = fopen($path.'/'.$name.'.jsx', 'w');
        $fileCss = fopen($path.'/'.$name.'.'.$this->styleType, 'w');
        fwrite($file, "
import React from 'react'
import './{$name}.{$this->styleType}'

export default function {$name}() {
    return (
        <h1>{$name}</h1>
    )
}
        
        ");
        fclose($file);
        fclose($fileCss);
        return "Generated \n {$path}/{$name}.jsx \n {$path}/{$name}.{$this->styleType}";
    }

    private function generateStore($name)
    {
        $path = $this->configPaths['store_path'];
        $nameMinus = strtolower($name);
        $file = fopen($path.'/'.$name.'Store.jsx', 'w');
        fwrite($file, 
"export const {$name}Context = React.createContext({ {$nameMinus}, set{$name} })

const {$name}Store = props => {
    const [{$nameMinus}, set{$name}] = useState(null)
    
    return (
        <{$name}Context.Provider value={{ {$nameMinus}, set{$name} }}>
        {props.children}
        </{$name}Context.Provider>
    )
}

export default AuthStore");
        fclose($file);
        return "Generated \n {$path}/{$name}Store.jsx";
    }

    private function generateView($name)
    {
        $path = $this->configPaths['view_path'];
        $pathRoute = $this->configPaths['router_path'];
        $nameMinus = strtolower($name);
        if(!file_exists($path.'/'.$nameMinus))
        {
            mkdir($path.'/'.$nameMinus);
        }
        $file = fopen($path.'/'.$nameMinus.'/'.$name.'.jsx', 'w');
        $fileCss = fopen($path.'/'.$nameMinus.'/'.$name.'.'.$this->styleType, 'w');
        fwrite($file, "
import React from 'react'
import './{$name}.{$this->styleType}'

export default function {$name}({ title }) {
    return (
        <h1>{title}</h1>
    )
}
        
        ");
        fclose($file);
        fclose($fileCss);

        $fileRoute = fopen($pathRoute.'/routes.js', 'r');
        $contents = fread($fileRoute, filesize($pathRoute.'/routes.js'));
        fclose($fileRoute);
        echo $this->handleRoutes($contents, $name, $nameMinus);
        return "Generated \n {$path}/{$nameMinus}/{$name}.jsx \n {$path}/{$nameMinus}/{$name}.{$this->styleType}";
    }


    private function handleRoutes($content, $name, $nameMinus)
    {
        $path = $this->configPaths['router_path'];
        $file = fopen($path.'/routes.js', 'w');
        $content = str_replace(']', 
        "    {
        path: '/{$nameMinus}',
        name: '{$nameMinus}',
        component: {$name},
        meta: {
            title: '{$name}'
        }
    },
]
    ", $content);
        $content = "import {$name} from '../views/{$nameMinus}/{$name}' \n{$content}";
        $content = trim($content);
        fwrite($file, $content);
        fclose($file);
        return "Updated \n {$path}/routes.js \n";;
    }
}