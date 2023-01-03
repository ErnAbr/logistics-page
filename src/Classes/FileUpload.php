<?php
namespace App\Classes;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

class BlogSorter
{
    var $id, $title, $content, $date, $slug, $imagename;
    public function blogSorter($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->date = $data['date'];
        $this->slug = $data['slug'];
        $this->imagename = $data['imagename'];
    }

    function data2Object($data)
    {
        $class_object = new BlogSorter($data);
        return $class_object;
    }

    function comparator($object1, $object2)
    {
        return $object1->slug > $object2->slug;
    }
}