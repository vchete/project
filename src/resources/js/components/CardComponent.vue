<template>
    <div>
        <div class="row" v-if="pageTitle">
            <slot name="alert"></slot>
            <div class="col-sm-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h3 class="mb-0 font-size-24">{{ pageTitle }} <span v-if="subTitle">|</span> <small v-html="subTitle" style="font-size:50%;"></small></h3>
                    <div class="page-title-right">
                        <span v-html="breadCrumb"></span> 
                    </div>
                </div>
            </div>
        
        </div>
        <div class="card" :class="classCard">
            <div class="card-header" v-if="!simple">
                <slot name="header"></slot>
            </div>
            <div class="card-body">
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            breadCrumb: {
                type: String,
                default: null,
            },
            pageTitle: {
                type: String,
                default: null
            },
            subTitle: {
                type: String,
                default: null
            },
            simple: {
                type: Boolean,
                default: true
            },
            classCard: {
                type: String,
                default: null
            }
        }
    }
</script>
