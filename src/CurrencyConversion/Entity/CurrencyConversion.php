<?php

namespace App\CurrencyConversion\Entity;

use App\CurrencyConversion\Repository\CurrencyConversionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyConversionRepository::class)]
class CurrencyConversion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $provider;

    #[ORM\Column(type: 'string', length: 10)]
    private $base_currency;

    #[ORM\Column(type: 'string', length: 10)]
    private $target_currency;

    #[ORM\Column(type: 'float')]
    private $value;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->base_currency;
    }

    public function setBaseCurrency(string $base_currency): self
    {
        $this->base_currency = $base_currency;

        return $this;
    }

    public function getTargetCurrency(): ?string
    {
        return $this->target_currency;
    }

    public function setTargetCurrency(string $target_currency): self
    {
        $this->target_currency = $target_currency;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        if ($created_at instanceof \DateTime) {
            $created_at = \DateTimeImmutable::createFromMutable($created_at);
        }

        $this->created_at = $created_at;

        return $this;
    }

    public function toArray()
    {
        return [
            'provider' => $this->getProvider(),
            'base_currency' => $this->getBaseCurrency(),
            'target_currency' => $this->getTargetCurrency(),
            'value' => $this->getValue(),
        ];
    }
}
