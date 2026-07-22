@props(['nodes','target'])

@php
$data = $nodes->map(fn($n) => [
'id' => $n->id,
'parent' => $n->parent_id,
'level' => $n->level,
'name' => $n->name_es, // ← confirma el nombre real de la columna
'color' => $n->color,
])->values();
@endphp

<div
    x-data="{
        nodes: {{ Js::from($data) }},
        selected: @entangle($target),
        children(pid) { return this.nodes.filter(n => n.parent === pid) },
        roots()      { return this.nodes.filter(n => n.level === 0) },
        isSel(id)  { return this.selected.some(x => x.ref === id) },
        descendants(id) {
            let out = [];
            for (const k of this.nodes.filter(n => n.parent === id)) {
                out.push(k.id); out = out.concat(this.descendants(k.id));
            }
            return out;
        },
        toggle(node) {
            if (this.isSel(node.id)) {
                const rm = [node.id, ...this.descendants(node.id)];
                this.selected = this.selected.filter(x => !rm.includes(x.ref));
            } else {
                this.selected = [...this.selected, { ref: node.id, level: node.level }];
            }
        },
    }"
    class="flex flex-wrap gap-3 w-full">
    <template x-for="root in roots()" :key="root.id">
        <div>
            {{-- NIVEL 0 --}}
            <button type="button" @click="toggle(root)"
                :style="isSel(root.id) ? `background:${root.color};color:#fff` : `border:1.5px solid ${root.color};background:color-mix(in srgb, ${root.color} 25%, transparent);color:${root.color}`"
                class="px-3 py-1 rounded-full text-sm transition-colors">
                <span x-text="root.name"></span>
            </button>

            {{-- NIVEL 1 (solo si root marcado) --}}
            <template x-if="isSel(root.id)">
                <div class="ml-5 mt-2 flex flex-col gap-2 border-l pl-3" style="border-color: var(--color-ui-button-light)">
                    <template x-for="l1 in children(root.id)" :key="l1.id">
                        <div>
                            <button type="button" @click="toggle(l1)"
                                :style="isSel(l1.id) ? `background:${l1.color};color:#fff` : `border:1.5px solid ${l1.color};background:color-mix(in srgb, ${root.color} 25%, transparent);color:${l1.color}`"
                                class="px-2.5 py-0.5 rounded-full text-xs transition-colors">
                                <span x-text="l1.name"></span>
                            </button>

                            {{-- NIVEL 2 (solo si l1 marcado) --}}
                            <template x-if="isSel(l1.id)">
                                <div class="ml-5 mt-1.5 flex flex-wrap gap-1.5">
                                    <template x-for="l2 in children(l1.id)" :key="l2.id">
                                        <button type="button" @click="toggle(l2)"
                                            :style="isSel(l2.id) ? `background:${l2.color};color:#fff` : `border:1.5px solid ${l2.color};background:color-mix(in srgb, ${root.color} 25%, transparent);color:${l2.color}`"
                                            class="px-2.5 py-0.5 rounded-full text-xs transition-colors">
                                            <span x-text="l2.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>