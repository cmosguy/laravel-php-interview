<?php


namespace App\Modules\Pagination;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator implements Arrayable, Jsonable
{
    /**
     * @var LengthAwarePaginator
     */
    private $paginator;

    /**
     * @var Request
     */
    private $request;

    public function __construct(LengthAwarePaginator $paginator, Request $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
    }

    public function toArray(): array
    {
        return [
            'current_page' => $this->paginator->currentPage(),
            'data' => $this->paginator->items(),
            'from' => $this->paginator->firstItem(),
            'last_page' => $this->paginator->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->paginator->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->paginator->lastItem(),
            'total' => $this->paginator->total(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    private function nextPageUrl(): ?string
    {
        if ($this->paginator->hasMorePages()) {
            return $this->url($this->paginator->currentPage() + 1);
        }

        return null;
    }

    private function path(): string
    {
        return $this->request->url();
    }

    private function previousPageUrl(): ?string
    {
        if ($this->paginator->currentPage() > 1) {
            return $this->url($this->paginator->currentPage() - 1);
        }

        return null;
    }

    private function url(int $page): string
    {
        if ($page <= 0) {
            $page = 1;
        }
        $query = array_merge($this->request->query->all(), [$this->paginator->getPageName() => $page]);

        return $this->path() . "?" . http_build_query($query);
    }
}

