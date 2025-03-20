<template>
<div class="cardInfo my-4 flex flex-col gap-2 h-[320px] max-h-[350px] overflow-y-auto scrollbar-custom">
                            <div
                                class="anuncio mr-2 flex justify-between gap-3 items-center border border-secundary-light rounded-lg p-3">
                                <div class="iconoIfo flex items-center gap-3">
                                    <span class="material-symbols-rounded bg-guru-primary p-3 rounded-lg">
                                        privacy_tip
                                    </span>
                                    <div class="info">
                                        <p>Se ha "Accion" el productod: "Nombre del producto"</p>
                                        <div class="creacion-id flex justify-between text-[12px]">
                                            <p>Creado por: <span>"Nombre usuario"</span>.</p>
                                            <p>ID movimiento: <span>"Numero en la DB"</span>.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="fecha-categoria flex flex-col items-end gap-2 text-[12px]">
                                    <p>Fecha creacion: <span>"Fecha del movimiento"</span></p>
                                    <p class="bg-guru-primary rounded-[5px] p-1">
                                        Nombre Tag
                                    </p>
                                </div>
                            </div>

                        </div>
</template>