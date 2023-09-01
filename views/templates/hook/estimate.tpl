<h2>{l s="Información de Solicitud de presupuesto" mod='estimate'} #{$estimate->id}</h2>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">{l s="Información personal" mod='estimate'}</div>
            <div class="panel-body">
                <ul>
                    <li><strong>{l s="Nombre completo:" mod='estimate'}</strong> {$estimate->fullname}</li>
                    <li><strong>{l s="Teléfono:" mod='estimate'}</strong> {$estimate->phone}</li>
                    <li><strong>{l s="Correo:" mod='estimate'}</strong> <a href="mailto:{$estimate->email}">{$estimate->email}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">{l s="Descripción de la solicitud" mod='estimate'}</div>
            <div class="panel-body">
                <ul>
                    <li><strong>{l s="Descripción:" mod='estimate'}</strong> {$estimate->description}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">{l s="Información del Producto" mod='estimate'}</div>
            <div class="panel-body">
                <ul>
                    <li><strong>{l s="ID:" mod='estimate'}</strong> {$product->id}</li>
                    <li><strong>{l s="Nombre:" mod='estimate'}</strong> {$product->name[$id_lang]}</li>
                    <li><strong>{l s="Referencia:" mod='estimate'}</strong> {$product->reference}</li>
                    <li><strong>{l s="Descripción:" mod='estimate'}</strong> {$product->description[$id_lang]}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
    
<a class="btn btn-default" href="{$link->getAdminLink('AdminModules')}&configure=estimate">
    <i class="process-icon-back"></i>
    {l s="Regresar a la lista" mod='estimate'}
</a>

<style>
    #content.bootstrap .panel-primary .panel-heading {
        color: #fff;
    }
</style>
