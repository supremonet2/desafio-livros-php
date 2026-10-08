@push('styles')
<style>
    #modal_backlog .backlog-markdown {
        max-width: 900px;
        line-height: 1.7;
    }

    #modal_backlog .backlog-markdown h1 {
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    #modal_backlog .backlog-markdown h2 {
        border-bottom: 1px solid var(--bs-border-color);
        font-size: 1.2rem;
        font-weight: 700;
        margin: 2rem 0 1rem;
        padding-bottom: .6rem;
    }

    #modal_backlog .backlog-markdown p {
        color: var(--bs-secondary-color);
    }

    #modal_backlog .backlog-markdown ol,
    #modal_backlog .backlog-markdown ul {
        padding-left: 1.5rem;
    }

    #modal_backlog .backlog-markdown li {
        margin-bottom: .8rem;
        padding-left: .25rem;
    }

    #modal_backlog .backlog-markdown ul:has(> li > input[type="checkbox"]) {
        list-style: none;
        padding-left: 0;
    }

    #modal_backlog .backlog-markdown li:has(> input[type="checkbox"]) {
        background: var(--bs-tertiary-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: .5rem;
        margin-bottom: .6rem;
        padding: .7rem .9rem;
    }

    #modal_backlog .backlog-markdown li input[type="checkbox"] {
        margin-right: .5rem;
    }

    #modal_backlog .backlog-markdown code {
        background: var(--bs-tertiary-bg);
        border-radius: .3rem;
        color: var(--bs-emphasis-color);
        padding: .15rem .35rem;
        overflow-wrap: anywhere;
    }
</style>
@endpush

<div class="modal fade" id="modal_backlog" tabindex="-1" aria-labelledby="titulo-modal-backlog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title fs-5" id="titulo-modal-backlog">Backlog do projeto</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="backlog-markdown bg-body border rounded-3 mx-auto p-2 p-lg-2">
                    {!! \Illuminate\Support\Str::markdown(file_get_contents(base_path('docs.md')), ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                </div>
            </div>

        </div>
    </div>
</div>