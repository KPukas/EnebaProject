<?php

namespace App\Controller;

use Alias\Http\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use function Symfony\Component\String\U;


class EnebaController extends AbstractController
{
    /**
     * @Route("/", name="eneba")
     */
    public function index()
    {
      $strings = array("Kad Tave kur pekūnas!", "Po paraliukais!", "Po šimts pypkių!", "Kad Tave sliekas prarytų ir į gabaliukus padalytų!", "Reikalingas Tu man, kaip šuniui penkta koja!");
      return $this->render('eneba/index.html.twig', [
              'controller_name' => 'EnebaController',
              'sentence' => $strings[array_rand($strings)]
          ]);
    }


    /**
     * @Route("/{code}", name="save_sentence")
     */
     public function saveSentence(Request $request)
     {
       $code = $request->query->get('code');
       $sentence = $request->query->get('sentence');
       $finder = new Finder();
       $finder->files()->in(__DIR__)->name("customUrls.txt");
       foreach ($finder as $file) {
         $content = $file->getContents();
         $lines = u($content)->split('\n');
         if($lines) {
           foreach ($lines as $line) {
             $split = u($line)->split('-');
             if($split[0] == $code){
               $sentence = "Failed to save the sentence!";
             } else {
               $newCode = $code . ',' .
               file_put_contents($file, $content, $sentence);
             }
           }
         }
       }
       return $this->render('eneba/sentence.html.twig', [
               'controller_name' => 'EnebaController',
               'sentence' => $sentence
           ]);
     }

     /**
      * @Route("/{code}")
      */
      public function loadSentence($code)
      {
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name("customUrls.txt");
        $sentence = "";
        foreach ($finder as $file) {
          $content = $file->getContents();
          $lines = u($content)->split('\n');
          if($lines) {
            foreach ($lines as $line) {
              $split = $line->split('-');
              if($split[0] == $code){
                $sentence = $split[1];
              }
            }
          }
      }
      if(!$sentence){
        $sentence = __DIR__;
      }
      return $this->render('eneba/sentence.html.twig', [
              'controller_name' => 'EnebaController',
              'sentence' => $sentence
          ]);
  }
}
