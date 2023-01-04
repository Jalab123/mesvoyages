<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of VisiteValidationsTest
 *
 * @author pilou
 */
class VisiteValidationsTest extends KernelTestCase{
    /**
     * Création d'un objet de type Visite, avec informations minimales
     * @return Visite
     */
    public function getVisite(): Visite{
        return (new Visite())
                ->setVille("New York")
                ->setPays("USA");
    }

    /**
     * Utilisaiton du Kernel pour tester une règle de validation
     * @param Visite $visite
     * @param int $nbErreursAttendues
     * @param string $message
     */
    public function assertErrors(Visite $visite, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($visite);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }

    public function testValidNoteVisite(){
        $visite = $this->getVisite()->setNote(10);
        $this->assertErrors($visite, 0);
    }
    
    public function testNonValidNoteVisite(){
        $visite = $this->getVisite()->setNote(21);
        $this->assertErrors($visite, 1);
    }
    
    public function testNonValidTempmaxVisite(){
        $visite = $this->getVisite()
                ->setTempmin(8)
                ->setTempmax(-8);
        $this->assertErrors($visite, 1, "min=20, max=18 devrait échouer");
    }
    
    public function testA(){
        $visite = $this->getVisite()->setNote(10);
        $this->assertErrors($visite, 0);
        $visite = $this->getVisite()->setNote(0);
        $this->assertErrors($visite, 0);
        $visite = $this->getVisite()->setNote(20);
        $this->assertErrors($visite, 0);
        $visite = $this->getVisite()->setNote(-1);
        $this->assertErrors($visite, 1);
        $visite = $this->getVisite()->setNote(21);
        $this->assertErrors($visite, 1);
        $visite = $this->getVisite()->setNote(-5);
        $this->assertErrors($visite, 1);
        $visite = $this->getVisite()->setNote(27);
        $this->assertErrors($visite, 1);
    }
    
    public function testB(){
        $visite = $this->getVisite()
                ->setTempmin(-5)
                ->setTempmax(30);
        $this->assertErrors($visite, 0);
        $visite = $this->getVisite()
                ->setTempmin(12)
                ->setTempmax(13);
        $this->assertErrors($visite, 0);
        $visite = $this->getVisite()
                ->setTempmin(12)
                ->setTempmax(-15);
        $this->assertErrors($visite, 1);
        $visite = $this->getVisite()
                ->setTempmin(10)
                ->setTempmax(10);
        $this->assertErrors($visite, 1);
    }
    
    public function testC(){
        $datejour = new \DateTime();
        $visite = $this->getVisite()
                ->setDatecreation($datejour);
        $this->assertErrors($visite, 0);
        $datejour = (new \DateTime())->sub(new \DateInterval("P5D"));
        $visite = $this->getVisite()
                ->setDatecreation($datejour);
        $this->assertErrors($visite, 0);
                $datejour = new \DateTime();
        $visite = $this->getVisite()
                ->setDatecreation($datejour);
        $this->assertErrors($visite, 0);
        $datejour = (new \DateTime())->add(new \DateInterval("P1D"));
        $visite = $this->getVisite()
                ->setDatecreation($datejour);
        $this->assertErrors($visite, 1);
        $datejour = (new \DateTime())->add(new \DateInterval("P5D"));
        $visite = $this->getVisite()
                ->setDatecreation($datejour);
        $this->assertErrors($visite, 1);
    }
}
