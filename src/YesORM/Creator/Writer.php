<?php

namespace YesORM\Creator;

use YesORM\Creator;

class Writer {

    /** @var Creator  */
    private $creator;

    function __construct(Creator $creator) {
        $this->creator = $creator;
    }

    function build() {
        $this->cleanup();
        $this->writeORM();
        $this->writeTables();
    }

    private function cleanup() {
        $iterator = new \DirectoryIterator($this->getDir());
        foreach ($iterator as $file) {
            /** @var \DirectoryIterator $file */
            if($file->isFile()) {
                unlink($file->getRealPath());
            }
        }

    }

    private function writeORM() {
        $ns = $this->creator->getNs();
        $tables = $this->creator->getTables();
        ob_start();
        include __DIR__ . "/templates/orm_template.php";
        $class = ob_get_clean();

        $this->writeToFile($class, 'ORM.php');

    }

    private function writeTables() {
        foreach ($this->creator->getTables() as $table) {
            $ns = $this->creator->getNs();
            $columns = $this->creator->getColumns($table->name());
            $referencingKeys = $this->creator->getReferencingTableKeys($table->name());
            $referencedKeys = $this->creator->getReferencedTableKeys($table->name());
            $trait = null;
            if($this->hasTableTrait($table)) {
                $trait = 'traits\\'.$table->name();
            }
            ob_start();
            include __DIR__ . "/templates/table_template.php";
            $class = ob_get_clean();

            $this->writeToFile($class, $table->name().".php");
        }
    }

    private function hasTableTrait(Table $table) {
        return file_exists($this->getTraitsDir()."/".$table->name().".php");
    }

    private function getDir() {
        $path = $this->creator->getLibPath() . "/" . $this->creator->getNs();
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    private function getTraitsDir() {
        $path = $this->getDir() . "/traits";
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    private function writeToFile($content, $fileName) {
        $filePath = $this->getDir() . "/{$fileName}";
        file_put_contents($filePath, $content);
    }

}