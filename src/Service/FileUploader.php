<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    protected $targetDirectory;
    protected $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function uploading(UploadedFile $file)
    {
        $originalFileName = pathinfo($file->
            getClientOriginalName(), PATHINFO_FILENAME);
        $setFileName = $this->slugger->slug($originalFileName);
      
        $setMimeType = $file->guessExtension();

        $fileName = $setFileName.'.'.$setMimeType;
    
        try{
        $file->move($this->getTargetDirectory(), $fileName);
         } catch(FileException $e){
         }    

         return $fileName;
    }

    public function getTargetDirectory()
        {
            return $this->targetDirectory;
        }
}