<?php

namespace App\Services;


class FaceVerificationService
{
    /**
     * Verify uploaded face against stored descriptor.
     *
     * @param array $storedDescriptor
     * @param array $currentDescriptor
     * @return bool
     */
    public function verify(array $storedDescriptor, array $currentDescriptor): bool
    {
        // TODO: Compare descriptors

        return false;
    }
}