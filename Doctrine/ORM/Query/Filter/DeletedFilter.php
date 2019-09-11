<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine\ORM\Query\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField('deletedAt')) {
            $field = $targetTableAlias.'.'.$targetEntity->getColumnName('deletedAt');

            return $field.' > now() OR '.$field.' IS NULL';
        }

        return '';
    }
}
