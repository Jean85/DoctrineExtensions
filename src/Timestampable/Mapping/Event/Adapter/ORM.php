<?php

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gedmo\Timestampable\Mapping\Event\Adapter;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\FieldMapping;
use Gedmo\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Gedmo\Mapping\Event\ClockAwareAdapterInterface;
use Gedmo\Timestampable\Mapping\Event\TimestampableAdapter;
use Psr\Clock\ClockInterface;

/**
 * Doctrine event adapter for ORM adapted
 * for Timestampable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 */
final class ORM extends BaseAdapterORM implements TimestampableAdapter, ClockAwareAdapterInterface
{
    /**
     * @var ClockInterface|null
     */
    private ?ClockInterface $clock = null;

    public function setClock(ClockInterface $clock): void
    {
        $this->clock = $clock;
    }

    /**
     * @param ClassMetadata $meta
     */
    public function getDateValue($meta, $field)
    {
        $mapping = $meta->getFieldMapping($field);

        return $this->getObjectManager()->getConnection()->convertToPHPValue(
            $this->getRawDateValue($mapping),
            $mapping instanceof FieldMapping ? $mapping->type : ($mapping['type'] ?? Types::DATETIME_MUTABLE)
        );
    }

    /**
     * Generates current timestamp for the specified mapping
     *
     * @param array<string, mixed>|FieldMapping $mapping
     *
     * @return \DateTimeInterface|int
     */
    private function getRawDateValue($mapping)
    {
        $datetime = $this->clock instanceof ClockInterface ? $this->clock->now() : new \DateTimeImmutable();
        $type = $mapping instanceof FieldMapping ? $mapping->type : ($mapping['type'] ?? '');

        if ('integer' === $type) {
            return (int) $datetime->format('U');
        }

        if (in_array($type, ['date_immutable', 'time_immutable', 'datetime_immutable', 'datetimetz_immutable'], true)) {
            return $datetime;
        }

        return \DateTime::createFromImmutable($datetime);
    }
}
