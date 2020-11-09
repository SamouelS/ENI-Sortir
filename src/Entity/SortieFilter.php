<?php
namespace App\Entity;

class SortieFilter
{
    private $campus;
    private $like;
    private $dateDebut;
    private $dateFin;
    private $etreOrganisateur;
    private $etreInscrit;
    private $pasInscrit;
    private $passer;

    

    /**
     * Get the value of campus
     */ 
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * Set the value of campus
     *
     * @return  self
     */ 
    public function setCampus($campus)
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * Get the value of like
     */ 
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set the value of like
     *
     * @return  self
     */ 
    public function setLike($like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get the value of dateDebut
     */ 
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set the value of dateDebut
     *
     * @return  self
     */ 
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get the value of dateFin
     */ 
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set the value of dateFin
     *
     * @return  self
     */ 
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get the value of etreOrganisateur
     */ 
    public function getEtreOrganisateur()
    {
        return $this->etreOrganisateur;
    }

    /**
     * Set the value of etreOrganisateur
     *
     * @return  self
     */ 
    public function setEtreOrganisateur($etreOrganisateur)
    {
        $this->etreOrganisateur = $etreOrganisateur;

        return $this;
    }

    /**
     * Get the value of etreInscrit
     */ 
    public function getEtreInscrit()
    {
        return $this->etreInscrit;
    }

    /**
     * Set the value of etreInscrit
     *
     * @return  self
     */ 
    public function setEtreInscrit($etreInscrit)
    {
        $this->etreInscrit = $etreInscrit;

        return $this;
    }

    /**
     * Get the value of pasInscrit
     */ 
    public function getPasInscrit()
    {
        return $this->pasInscrit;
    }

    /**
     * Set the value of pasInscrit
     *
     * @return  self
     */ 
    public function setPasInscrit($pasInscrit)
    {
        $this->pasInscrit = $pasInscrit;

        return $this;
    }

    /**
     * Get the value of passer
     */ 
    public function getPasser()
    {
        return $this->passer;
    }

    /**
     * Set the value of passer
     *
     * @return  self
     */ 
    public function setPasser($passer)
    {
        $this->passer = $passer;

        return $this;
    }

}