<?php

    /** Utilitários usados nos testes. */

    /**
     * Remove um diretório recursivamente.
     * @param string $object_dir Diretório a ser removido completamente.
     */
    $helper->add("rmdir_recursively", function($object_dir) {
        // Se o diretório não existir, finaliza.
        if(!is_dir($object_dir)) {
            return;
        }

        // Remove um diretório recursivamente.
        $directory_files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($object_dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach($directory_files as $directory_file) {
            // Remove um diretório.
            if($directory_file->isDir()) {
                rmdir($directory_file->getRealPath());
                continue;
            }

            // Remove um arquivo.
            unlink($directory_file->getRealPath());
        }

        // Remove o próprio diretório.
        unset($directory_files);
        rmdir($object_dir);
    });
