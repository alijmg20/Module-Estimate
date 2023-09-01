<div class="panel">
    <h3><i class="icon-list-ul"></i> {l s='Formularios recibidos' mod='estimate'}</h3>
    <div id="lista-profesionales">
        <div id="profesionales">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Descripción</th>
                        <th>Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$estimates item=estimate}
                        <tr>
                            <td>{$estimate.id_estimate}</td>
                            <td>{$estimate.fullname}</td>
                            <td>{$estimate.phone}</td>
                            <td>{$estimate.email}</td>
                            <td>{$estimate.description}</td>
                            <td>{$estimate.product_name}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">Acciones</button>
                                    <div class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item btn-success"
                                            href="{$link->getAdminLink('AdminModules')}&configure=estimate&view_id={$estimate.id_estimate}">
                                            <i class="icon-eye"></i>
                                            {l s='View' d='Admin.Actions'}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn-danger"
                                            href="{$link->getAdminLink('AdminModules')}&configure=estimate&delete_id={$estimate.id_estimate}">
                                            <i class="icon-trash"></i>
                                            {l s='Delete' d='Admin.Actions'}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>