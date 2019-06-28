<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayementRepository")
 */
class Payement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_villo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_villo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_villo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname_customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname_customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdVillo(): ?int
    {
        return $this->id_villo;
    }

    public function setIdVillo(int $id_villo): self
    {
        $this->id_villo = $id_villo;

        return $this;
    }

    public function getNameVillo(): ?string
    {
        return $this->name_villo;
    }

    public function setNameVillo(string $name_villo): self
    {
        $this->name_villo = $name_villo;

        return $this;
    }

    public function getAddressVillo(): ?string
    {
        return $this->address_villo;
    }

    public function setAddressVillo(string $address_villo): self
    {
        $this->address_villo = $address_villo;

        return $this;
    }

    public function getFirstnameCustomer(): ?string
    {
        return $this->firstname_customer;
    }

    public function setFirstnameCustomer(string $firstname_customer): self
    {
        $this->firstname_customer = $firstname_customer;

        return $this;
    }

    public function getLastnameCustomer(): ?string
    {
        return $this->lastname_customer;
    }

    public function setLastnameCustomer(string $lastname_customer): self
    {
        $this->lastname_customer = $lastname_customer;

        return $this;
    }

    public function getEmailCustomer(): ?string
    {
        return $this->email_customer;
    }

    public function setEmailCustomer(string $email_customer): self
    {
        $this->email_customer = $email_customer;

        return $this;
    }
}
