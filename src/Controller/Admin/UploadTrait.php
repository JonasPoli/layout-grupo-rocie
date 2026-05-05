<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

trait UploadTrait
{
    private function handleUpload(
        UploadedFile $file,
        SluggerInterface $slugger,
        string $subdir,
        ?string $oldFilename = null
    ): ?string {
        // Ignore empty / invalid submissions (form sent without choosing a file)
        if (!$file->isValid() || $file->getSize() === 0) {
            return null;
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $subdir;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $safe = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $ext  = $file->guessExtension() ?? $file->getClientOriginalExtension();
        $name = $safe . '-' . uniqid() . '.' . $ext;

        try {
            $file->move($uploadDir, $name);

            // Only remove old file after the new one has been saved successfully
            if ($oldFilename) {
                $old = $uploadDir . '/' . $oldFilename;
                if (file_exists($old)) {
                    @unlink($old);
                }
            }

            return $name;
        } catch (\Throwable) {
            return null;
        }
    }
}
