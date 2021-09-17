<template>
    <div>
        <card-component :pageTitle="pageTitle" :simple="true" :subTitle="cSubTitle">
            <template v-slot:alert>
                <alert-component ref="alert"></alert-component>
            </template>
            <template v-slot:body>
                <data-table-component 
                    ref="dataTable"
                    v-show="crudIndexShow" 
                    :columns="cColumns" 
                    :permissions="moduleAccess" 
                    :route="route" 
                    :crud="true" 
                    :options="options"
                    :exports="exports" 
                    :m-create="mEditOrCreate"
                    :m-edit="mEditOrCreate"
                    :m-destroy="mDestroy"
                    @mShowAlertMessage="mShowAlertMessage" />
                <crud-edit-component 
                    v-if="crudEditShow" 
                    @mChangeComponent="mChangeComponent"
                    @mSave="mSave"
                    :fields="fields"
                    :errors="errorsFields"
                    :dataEdit="cDataEdit"
                    @mResetSubTitle="mResetSubTitle"
                    @mResetErrors="mResetErrors" />
            </template>
        </card-component>
        <confirm-component></confirm-component>
    </div>
</template>

<script>
    import CrudEditComponent from 'components/Crud/EditComponent'
    import AlertComponent from 'components/AlertComponent'
    import loading from 'utils/loading'
    import util from 'utils/util'

    export default {
        components: { CrudEditComponent, AlertComponent },
        data() {
            return {
                ...dataVue,
                subTitle: null,
                route: document.location.pathname,
                routeAction: null,
                crudIndexShow: true,
                crudEditShow: false,
                fields: [],
                errorsFields: {},
                dataEdit: {},
                method: null,
            }
        },
        computed: {
            cColumns () {
                return this.columns.map((item) => {
                    return {
                        key     : item.fieldAs,
                        label   : item.name,
                        type    : item.type,
                        decimals: item.decimals,
                        class   : item.class
                    }
                });
            },
            cSubTitle () {
                return this.subTitle
            },
            cDataEdit () {
                if (this.dataEdit) {
                    this.fields.forEach(f => {
                        if (f.tipo === 'numeric') {
                            this.dataEdit[f.field] = this.dataEdit[f.field] ? parseInt(this.dataEdit[f.field]).toFixed(f.decimales) : this.dataEdit[f.field];
                        }
                    });
                }
                return this.dataEdit
            }
        },
        methods: {
            mEditOrCreate (value, isNew = false) {
                let route = this.route + (isNew ? '/create' : ('/' + value + '/edit'))
                loading.ini(async () => {
                    await axios.get(route, {
                        params: {
                            responseJson: true,
                            id: value
                        }
                    }).then(response => {
                        let res          = response.data;
                        this.fields      = res.data.fields;
                        this.dataEdit    = res.data.data ? res.data.data : this.mCreateObject()
                        this.method      = res.data.data ? 'put' : 'post'
                        this.subTitle    = `<i class="fas fa-${ res.data.data ? 'pen' : 'plus'}"></i> ${ res.data.data ? 'Editar' : 'Nuevo'}`
                        this.routeAction = res.data.route;
                        this.mChangeComponent();
                    }).catch(error => {
                        let err = error.response ? error.response.data.message : error;
                        this.mShowAlertMessage(err, 'danger');
                    });
                })
            },
            mSave (data) {
                this.mResetErrors()
                loading.ini(async () => {
                    await axios({
                        method: this.method,
                        url: this.routeAction,
                        data: data
                    }).then(async response => {
                        let res = response.data;
                        if (res.status == 422) {
                            this.errorsFields = res.errors
                            this.mShowAlertMessage(res.message, 'danger');
                            util.scrollTo();
                            return false
                        }
                        if (res.status != 200) {
                            this.mShowAlertMessage(res.message, 'danger');
                            util.scrollTo();
                            return false
                        }
                        this.mChangeComponent()
                        this.mResetSubTitle()
                        await this.$refs.dataTable.getRows();
                        this.mShowAlertMessage(res.message);
                    }).catch(error => {
                        let err = error.response ? error.response.data.message : error;
                        this.mShowAlertMessage(err, 'danger');
                    });
                }, 'Guardando el registro...')
            },
            mDestroy (value) {
                this.$confirm({
                    title: '<i class="far fa-trash-alt text-muted"></i> Eliminar Registro',
                    message: '¿Está seguro de eliminar el registro?',
                    callback: confirm => {
                        if (confirm) {
                            loading.ini(async () => {
                                await axios.delete(this.route + '/' + value, {
                                    params: {
                                        responseJson: true,
                                        id: value
                                    }
                                }).then(async response => {
                                    let res = response.data;

                                    if (res.status != 200) {
                                        this.mShowAlertMessage(res.message, 'danger');
                                        util.scrollTo();
                                        return false
                                    }
                                    await this.$refs.dataTable.getRows();
                                    this.mShowAlertMessage(res.message, 'warning');
                                });
                            }, 'Eliminando el registro...').catch(error => {
                                let err = error.response ? error.response.data.message : error;
                                this.mShowAlertMessage(err, 'danger');
                                util.scrollTo();
                            });
                        }
                    }
                });
            },
            mCreateObject () {
                let object      = {}
                let parseFields = this.fields.map(item => {return {[item.fieldAs]: item.default} });
                for (let f of parseFields) {
                    Object.assign(object, f);
                }
                return  object
            },
            mChangeComponent () {
                this.crudIndexShow = !this.crudIndexShow;
                this.crudEditShow  = !this.crudEditShow;
            },
            mResetSubTitle () {
                this.subTitle = null
            },
            mResetErrors () {
                this.errorsFields = {}
            },
            mShowAlertMessage (message, type = 'success') {
                this.$refs.alert.mShow(message, type);
            }
        }
    }
</script>
