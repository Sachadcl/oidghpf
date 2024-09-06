<?php

namespace App\Class;

?>

<?php




class Filter
{
    private $name;
    private $beginDate;
    private $endDate;
    private $campusId;
    private $isOrganizer;
    private $isParticipant;
    private $notParticipant;
    private $finishedOutings;

    public function __construct($name = null, $beginDate = null, $endDate = null, $campusId = null, $isOrganizer = null, $isParticipant = null, $notParticipant = null, $finishedOutings = null)
    {
        $this->name = $name;
        $this->beginDate = $beginDate;
        $this->endDate = $endDate;
        $this->campusId = $campusId;
        $this->isOrganizer = $isOrganizer;
        $this->isParticipant = $isParticipant;
        $this->notParticipant = $notParticipant;
        $this->finishedOutings = $finishedOutings;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getBeginDate()
    {
        return $this->beginDate;
    }

    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function getCampusId()
    {
        return $this->campusId;
    }

    public function setCampusId($campusId)
    {
        $this->campusId = $campusId;
    }

    public function getIsOrganizer()
    {
        return $this->isOrganizer;
    }

    public function setIsOrganizer($isOrganizer)
    {
        $this->isOrganizer = $isOrganizer;
    }

    public function getIsParticipant()
    {
        return $this->isParticipant;
    }

    public function setIsParticipant($isParticipant)
    {
        $this->isParticipant = $isParticipant;
    }

    public function getNotParticipant()
    {
        return $this->notParticipant;
    }

    public function setNotParticipant($notParticipant)
    {
        $this->notParticipant = $notParticipant;
    }

    public function getFinishedOutings()
    {
        return $this->finishedOutings;
    }

    public function setFinishedOutings($finishedOutings)
    {
        $this->finishedOutings = $finishedOutings;
    }
}
