<?php

namespace Modules\Inventory\Services\Settings;
use Modules\Inventory\Models\Settings\Tag;

class TagService
{
    
    public function getAll(int $limit = 20) {
        return Tag::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return Tag::create($data);
    }

    public function update(Tag $tag, array $data)
    {
        $tag->update($data);
        return $tag;
    }

    public function delete(Tag $tag)
    {
        $tag->delete();
    }

    public function show($id)
    {
        return Tag::findOrFail($id);
    }
}
