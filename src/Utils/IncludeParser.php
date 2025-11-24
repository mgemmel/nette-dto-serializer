<?php
declare(strict_types=1);

namespace NetteDtoSerializer\Utils;

final class IncludeParser
{
    /**
     * Parse array of strings like ['sport.competition.tournament.team', 'team1.coach']
     * → nested array
     *
     * @param string[] $includes
     * @return array<string, mixed>
     */
    public function parseIncludes(array $includes): array
    {
        $result = [];

        foreach ($includes as $include) {
            $parts = array_map('trim', explode('.', $include));
            $ref = &$result;

            foreach ($parts as $part) {
                if ($part === '') {
                    continue;
                }

                if (!isset($ref[$part])) {
                    $ref[$part] = [];
                }

                $ref = &$ref[$part];
            }

            unset($ref);
        }

        return $result;
    }
}
