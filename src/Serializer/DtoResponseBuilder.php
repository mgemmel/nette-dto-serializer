<?php
declare(strict_types=1);

namespace NetteDtoSerializer\Serializer;

use Closure;
use NetteDtoSerializer\Utils\IncludeParser;

final class DtoResponseBuilder
{
    /**
     * @param Item|Collection $dto
     * @param list<string> $requestedIncludes
     * @return mixed
     */
    public function create(Item|Collection $dto, array $requestedIncludes = []): mixed
    {
        if ($dto instanceof Collection) {
            $dto = $dto->all();
        }
        $includeParser = new IncludeParser();

        return $this->process($dto, $includeParser->parseIncludes($requestedIncludes));
    }

    /**
     * @param Item|Item[] $dto
     * @param string[] $requestedIncludes
     * @return mixed
     */
    private function process(Item|array $dto, array $requestedIncludes): mixed
    {
        if (is_array($dto)) {
            $processed = [];
            foreach ($dto as $item){
                if (!$item instanceof Item){
                    continue;
                }
                $processed[] = $this->process($item, $requestedIncludes);
            }
            return $processed;
        }

        $data = [];
        foreach (get_object_vars($dto) as $name => $value) {
            $childIncludes = $requestedIncludes[$name] ?? null;
            // lazy-loading Closure
            if ($value instanceof Closure) {
                if ($childIncludes === null){
                    continue;//skip property
                }
                $value = $value();
            }

            $data[$name] = $value instanceof Item || is_array($value)
                ? $this->process($value, $childIncludes ?? [])
                : $value;
        }

        return $data;
    }
}
