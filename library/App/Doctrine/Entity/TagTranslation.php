<?php
namespace App\Doctrine\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tag_locales", indexes={
 *      @ORM\index(name="tag_translation_idx", columns={"locale", "object_class", "foreign_key", "field"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class TagTranslation extends \Gedmo\Translatable\Entity\AbstractTranslation
{	
}