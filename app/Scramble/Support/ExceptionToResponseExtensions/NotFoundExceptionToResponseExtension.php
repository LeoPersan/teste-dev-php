<?php

namespace App\Scramble\Support\ExceptionToResponseExtensions;

use Dedoc\Scramble\Extensions\ExceptionToResponseExtension;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types as OpenApiTypes;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundExceptionToResponseExtension extends ExceptionToResponseExtension
{
    public function shouldHandle(Type $type): bool
    {
        return $type instanceof ObjectType
            && (
                $type->isInstanceOf(RecordsNotFoundException::class)
                || $type->isInstanceOf(NotFoundHttpException::class)
            );
    }

    public function toResponse(Type $type): Response
    {
        $validationResponseBodyType = (new OpenApiTypes\ObjectType())
            ->addProperty(
                'status',
                (new OpenApiTypes\StringType())
                    ->setDescription('"success" or "error"')
            )
            ->addProperty(
                'message',
                (new OpenApiTypes\StringType())
                    ->setDescription('Error overview.')
            )
            ->setRequired(['message']);

        return Response::make(404)
            ->description('Not found')
            ->setContent(
                'application/json',
                Schema::fromType($validationResponseBodyType)
            );
    }

    public function reference(ObjectType $type): Reference
    {
        return new Reference('responses', Str::start($type->name, '\\'), $this->components);
    }
}
