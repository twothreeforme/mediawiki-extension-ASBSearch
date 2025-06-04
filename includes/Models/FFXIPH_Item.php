<?php

class FFXIPH_Item {

    private string $descr;
    private string $name;
    private string $longname;
    private int $lvl;
    private string $flags;
    private string $jobs;
    private string $races;
    private int $id;
    private int $DATid = 0;

    public function __construct(){}

    public function getDescr(): string{ return $this->descr; }
    public function getName(): string{ return $this->name; }
    public function getLongname(): string{ return $this->longname; }
    public function getLvl(): int{ return $this->lvl; }
    public function getFlags(): string { return $this->flags; }
    public function getJobs(): string { return $this->jobs; }
    public function getRaces(): string { return $this->races; }
    public function getId(): int { return $this->id; }
    public function getDATid(): int { return $this->DATid; }

    public function setDescr(string $descr){ $this->descr = $descr; }
    public function setName(string $name){ $this->name = $name; }
    public function setLongname(string $long){ $this->longname = $long; }
    public function setLvl(int $lvl){ $this->lvl = $lvl; }
    public function setFlags(string $flags){ $this->flags = $flags; }
    public function setJobs(string $jobs){ $this->jobs = $jobs; }
    public function setRaces(string $races){ $this->races = $races; }
    public function setId(int $id){ $this->id = $id; }
    public function setDATid(int $dat){ $this->DATid = $dat; }

    public function importFromDAT(array $dat){
        $this->descr = $dat['descr'];
        $this->name = $dat['name'];
        $this->longname = $dat['longname'];
        $this->lvl = $dat['lvl'];
        $this->flags = $dat['flags'];
        $this->jobs = $dat['jobs'];
        $this->races = $dat['races'];
    }
}

?>