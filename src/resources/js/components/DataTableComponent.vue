<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <select class="form-control-select mb-2" @change="rowsLengthChange()" v-model="cOptions.rowsLength">
                    <option v-for="op of cOptions.rowsLengthOptions">{{ op }}</option>
                </select>
                <!-- <div class="btn-group dropright">
                    <a title="Columnas" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-grip-vertical text-muted fa-add-action-table"></i>
                    </a>
                    <div class="dropdown-menu" style="padding-left:5px; padding-left:5px;">
                        <template>
                            <div class="form-group form-check" v-for="col of cColumnsToFilter" :key="col.key" style="margin-bottom:0px;">
                                <label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="col.show"> {{ col.label }}</label>
                            </div>
                        </template>
                    </div>
                </div> -->
                <a title="Descargar a Excel" class="btn btn-sm" v-if="exports.includes('excel')"><i class="far fa-file-excel text-muted fa-add-action-table"></i></a>
                <a title="Descargar a PDF" class="btn btn-sm" v-if="exports.includes('pdf')"><i class="far fa-file-pdf text-muted fa-add-action-table"></i></a>
                <div class="float-right">
                    <a title="Actualizar datos" class="btn btn-sm" v-on:click="mRealoadDataTable"><i class="fa fa-sync text-muted fa-add-action-table"></i></a>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr class="table-active">
                    <th :colspan="cColumns.length + (crud ? 2 : 1)" class="default" style="border:0px solid">
                        <div class="float-right" style="padding-top: 5px;">
                            <button class="btn btn-sm btn-success btn-create" v-if="crud && permissions.create" @click="mCreate(idCreate, true)"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar..." v-model="search" v-on:keyup.enter="mSearch"> 
                            <div class="input-group-append" title="Click o Enter para buscar" v-on:click="mSearch">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th class="text-center" width="5%">No.</th>
                    <th v-for="col of cColumns" :key="col.key" class="text-center" style="postion:relative;" v-bind:style="[cOptions.widthColumns[col.key] ? {'width':  cOptions.widthColumns[col.key]+'%'} : '']">
                        <div style="position:relative; width:100%;">
                            {{ col.label }}
                            <div style="position:absolute; top:0px; width:100%;" class="text-right" v-if="!cOptions.noSortColumns.includes(col.key)">
                                <a v-if="orderBy.column == col.key" @click.prevent="mOrderBy(col.key)" class="columnOrder">
                                    <i v-if="orderBy.dir == 'asc'" class="fas fa-sort-amount-down text-muted"></i>
                                    <i v-if="orderBy.dir == 'desc'" class="fas fa-sort-amount-up text-muted"></i>
                                </a>
                                <a v-else @click.prevent="mOrderBy(col.key)" class="columnOrder"><i class="fas fa-sort-amount-up text-muted"></i></a>
                            </div>
                        </div>
                    </th>
                    
                    <th v-if="crud && (permissions.edit || permissions.destroy)" width="15%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="rows.length">
                        <tr v-for="(r, index) of rows" :key="index">
                            <td class="text-center" width="10px">{{ index + 1 }}</td>
                            <td v-for="(c, index) of columns" :key="index" :class="c.class">
                                <span v-if="c.type == 'numeric'">{{ r[c.key] ? parseInt(r[c.key]).toFixed(c.decimals) : '' }}</span>
                                <span v-else-if="c.type == 'bool'" v-html="r[c.key]"></span>
                                <span v-else>{{ r[c.key] }}</span>
                            </td>
                            <td v-if="crud && (permissions.edit || permissions.destroy)" class="text-center">
                                <button v-if="permissions.edit" class="btn btn-sm btn-primary" title="Editar" @click="mEdit(r.id)" ><i class="fas fa-pencil-alt"></i></button>
                                <button v-if="permissions.destroy" class="btn btn-sm btn-danger" title="Eliminar" @click="mDestroy(r.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                </template>
                <tr v-else><td :colspan="columns.length + (crud ? 2 : 1)" class="text-center">Sin datos para mostrar.</td></tr>
            </tbody>
            <tfoot>
                <slot name="tfooter"></slot>
                <tr>
                    <th :colspan="columns.length + (crud ? 2 : 1)">
                        <span class="font-size-13">Mostrando {{ recordsFiltered }} de {{ recordsTotal }} {{ recordsTotal == 1 ? 'registro' : 'registros' }}</span>
                        <div class="float-right">
                            <button class="btn btn-sm btn-secondary" v-on:click="mPagination('firstPage')"><i class="fas fa-angle-double-left"></i></button>
                            <button class="btn btn-sm btn-secondary" v-on:click="mPagination('previousPage')"><i class="fas fa-angle-left"></i></button>
                            <button class="btn btn-sm btn-secondary" v-on:click="mPagination('nextPage')"><i class="fas fa-angle-right"></i></button>
                            <button class="btn btn-sm btn-secondary" v-on:click="mPagination('lastPage')"><i class="fas fa-angle-double-right"></i></button>
                            <span class="font-size-15" :title="draw + ' de ' + cDataTablePages + (cDataTablePages == 1 ? ' página' : ' páginas')">{{ draw }}/{{ cDataTablePages || 1 }}</span>
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import axios from 'axios'
    import loading from 'utils/loading'
    export default {
        props: {
            columns: {
                type: Array,
                default: () => {return []},
            },
            options: {
                type: (Array, Object),
                default: () => {return {}},
            },
            permissions: {
                type: Object,
                default: () => {return []},

            },
            exports: {
                type: Array,
                default: () => {return []},

            },
            route: {
                type: String,
                required: true
            },
            crud: {
                type: Boolean,
                default: false
            },
            mCreate: Function,
            mEdit: Function,
            mDestroy: Function
        },
        data () {
            return {
                optionsDefault: {
                    widthColumns: [],
                    noSortColumns: [],
                    rowsLength: 10,
                    rowsLengthOptions: [5,10,25,50,100,500,1000],
                    exports: ['excel', 'pdf']
                },
                rows: [],
                draw: 1,
                start: 0,
                recordsFiltered: 0,
                recordsTotal: 0,
                search: '',
                idCreate: window.ceroCrypt,
                orderBy: {
                    column: null,
                    dir: 'asc',
                }
            }
        },
        mounted() {
            this.getRows();
        },
        computed: {
            cOptions () {
                if (Object.keys(this.options).length > 0) {
                    for (let op in this.options) {
                        if (this.optionsDefault[op]) {
                            this.optionsDefault[op] = this.options[op];
                        }
                    }
                }
                return this.optionsDefault
            },
            cColumnsToFilter () {
                this.columns.forEach(item => {
                    item.show = true
                });
                return this.columns;
            },
            cColumns () {
                return this.columns;
            },
            cDataTablePages () {
                let totalPage = Math.ceil((this.recordsTotal/this.cOptions.rowsLength));
                return totalPage > 9 ? (totalPage - 1) : totalPage;
            }
        },
        methods: {
            async getRows () {
                await loading.ini(async () => {
                    await axios.get(this.route, {
                        params: {
                            responseJson: true,
                            draw: this.draw,
                            start: this.start,
                            length: this.cOptions.rowsLength,
                            search: this.search,
                            order: this.orderBy
                        }
                    }).then(response => {
                        this.rows = response.data.data;
                        this.recordsFiltered = response.data.recordsFiltered;
                        this.recordsTotal = response.data.recordsTotal;
                    }).catch(error => {
                        let err = error.response ? error.response.data.message : error;
                        this.$emit('mShowAlertMessage', err, 'danger');
                    });
                })
            },
            rowsLengthChange() {
                this.getRows();
            },
            mSearch () {
                if (this.search !== '') {
                    this.draw = 1;
                }
                this.getRows();
            },
            mRealoadDataTable () {
                this.mResetOrderBy();
                this.mSearch();
            },
            mPagination (option) {
                if (this.draw > 1 && this.draw <= this.cDataTablePages) {
                    switch (option) {
                        case 'firstPage':
                            this.draw  = 1;
                            this.start = 0;
                            break;
                        case 'lastPage':
                            this.draw  = this.cDataTablePages;
                            this.start = this.draw * this.cOptions.rowsLength;
                            break;
                        case 'nextPage':
                            this.draw  ++;
                            this.start = this.start + this.cOptions.rowsLength;
                            break;
                        case 'previousPage':
                            this.draw  --;
                            this.start = this.start - this.cOptions.rowsLength;
                            break;
                    }
                    this.mSearch();
                }
            },
            mOrderBy (column) {
                if (this.orderBy.column && this.orderBy.column == column) {
                    if (this.orderBy.dir == 'asc') {
                        this.orderBy.dir = 'desc'
                    } else {
                        this.orderBy.dir = 'asc'
                    }
                } else {
                    this.orderBy.column = column
                    this.orderBy.dir = 'asc'
                }
                this.getRows(this.orderBy);
            },
            mResetOrderBy () {
                this.orderBy.column = null;
                this.orderBy.dir    = 'asc';
            }
        }
    }
</script>

<style lang="sass" scoped>
    .table thead th, .table tbody td, .table tfoot th, .table tfoot td
        padding:0.3rem
        vertical-align: middle
    .form-control
        display: inline
        width: 15% !important
        height: calc(1em + 0.75rem + 2px) !important
        border-radius: 0rem !important
    .input-group
        position: relative !important
        display: initial-flex !important
        width: 50% !important
        flex-wrap: initial !important
    .input-group .form-control
        border-radius: 0px !important
    .input-group-text
        border-radius: 0rem !important
    .form-control-select
        display: inline
        width: 15%
        height: calc(0.7em + 0.75rem + 2px)
        padding: 0rem
        font-size: 0.9rem
        font-weight: 400
        line-height: 1.6
        color: #495057
        background-color: #fff
        background-clip: padding-box
        border: 1px solid #ced4da
        border-radius: 0rem
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out
    .btn-create
        display: inline !important
        float: right !important
    .fa-add-action-table
        font-size: 1.2rem
    .btn-group, .btn-group-vertical
        margin-right: 1px !important
    .btn-secondary
        background-color: #a2a2a2 !important
        border-color: #a2a2a2 !important
    .columnOrder
        cursor: pointer !important
</style>
