<?php

namespace App\Modules\Customer\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\App\Modules\Customer\Repositories\CustomerRepository")
 * @ORM\Table(name="customers")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    // It must not be accessed before initialization
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected string $email;

    /**
     * @ORM\Column(type="string", name="first_name")
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", name="last_name")
     */
    protected string $lastName;

    /**
     * @ORM\Column(type="string")
     */
    protected string $username;

    /**
     * @ORM\Column(type="smallint")
     */
    protected string $gender;

    /**
     * @ORM\Column(type="string")
     */
    protected string $country;

    /**
     * @ORM\Column(type="string")
     */
    protected string $city;

    /**
     * @ORM\Column(type="string")
     */
    protected string $phone;

    /**
     * @ORM\Column(type="string")
     */
    protected string $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Customer
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Customer
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Customer
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return \sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): Customer
    {
        $this->username = $username;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender($gender): Customer
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): Customer
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): Customer
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Customer
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): Customer
    {
        $this->password = $password;

        return $this;
    }
}
