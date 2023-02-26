<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity()
 */
class Medium {
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=160)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false) 
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $knockedOutBgFile;

    /**
     * @ORM\Column(type="string", name="filetype", length=50, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $size;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId() {
        return $this->id;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getKnockedOutBgFile()
    {
        return $this->knockedOutBgFile;
    }

    /**
     * @param mixed $knockedOutBgFile
     */
    public function setKnockedOutBgFile($knockedOutBgFile): void
    {
        $this->knockedOutBgFile = $knockedOutBgFile;
    }
}
