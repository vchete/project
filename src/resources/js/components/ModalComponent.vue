<template>
    <div>
        <transition name="fade">
            <div class="modal" v-if="showModal">
                <div class="modal-dialog" :class="'modal-' + size" role="document">
                    <div class="modal-content">
                    <div class="modal-header" v-if="title">
                        <h5 class="modal-title">{{ title + (subTitle ? ' | ' : '') }} <small v-html="subTitle" style="font-size: 60%;"></small></h5>
                        <button type="button" class="close" @click="mClose">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <button type="button" class="btn btn-danger" @click="mClose">Cancelar</button>
                            <button type="button" class="btn btn-primary" @click="mAcept">Aceptar</button>
                        </slot>
                    </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>

export default {
    props: {
        title: {
            type: String,
            required: true
        },
        subTitle: {
            type: String,
            default: null
        },
        size: {
            type: String,
            default: 'md'
        }
    },
    data() {
        return {
            showModal: false
        }
    },
    methods: {
        mOpen () {
            this.showModal = true
            document.body.classList.add('modal-open');
            document.querySelector('#modal-backdrop-custom').style.display = 'block';
        },
        mClose () {
            document.body.classList.remove('modal-open')
            document.querySelector('#modal-backdrop-custom').style.display = 'none';
            this.showModal = false
        },
        mAcept () {
            this.$emit('mAcept');
        }
    },
}
</script>

<style lang="sass" scoped>
    .fade-enter-active, .fade-leave-active
        transition: opacity .5s
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
        opacity: 0
    .modal
        display: block !important
        z-index: 3000 !important
</style>