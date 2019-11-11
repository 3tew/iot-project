<template>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-content">
                                <div class="content">
                                    <h2>Parking Lot</h2>
                                    <p>ระบบแสดงสถานะที่จอดรถ</p>
                                </div>
                                <div class="content">
                                    <div class="content">
                                        <div class="table-container">
                                            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                                                <thead>
                                                    <tr>
                                                        <th>ช่องจอด</th>
                                                        <th class="has-text-centered">สถานะ</th>
                                                        <th>เวลาที่จอด(ชั่วโมง)</th>
                                                        <th>นับครั้ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="slot in slots" v-bind:key="slot.id">
                                                        <td>{{ slot.name }}</td>
                                                        <td v-if="slot.status === true" class="has-text-centered"><span class="has-text-danger"><i class="fas fa-times-circle"></i> <small>ไม่ว่าง</small></span></td>
                                                        <td v-else class="has-text-centered"><span class="has-text-success"><i class="fas fa-check-circle"></i> <small>ว่าง</small></span></td>
                                                        <td>{{ slot.status === true ? slot.presentTime : '-' }}</td>
                                                        <td>{{ slot.countIn }}</td>
                                                    </tr>
                                                    <tr id="data-loader">
                                                        <td colspan="4" class="has-text-centered" style="line-height: 1rem;"><div class="button is-loading" style="height: 1rem; border: 0px;"></div></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p class="has-text-centered">
                                                <small>อัพเดทล่าสุดเมื่อ<br><span>{{ dateTime }}</span></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="content" style="margin-top:2rem;">
                                        <router-link tag="a" to="/">
                                            <button class="button is-primary">
                                                <i class="fas fa-arrow-circle-right" style="margin-right:0.5rem;"></i>
                                                กลับหน้าหลัก
                                            </button>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        const appUrl = window.location.href.split('/')[2];
        const slots = [];
        const dateTime = "";

        return {
            componentName: 'Slot',
            appUrl,
            slots,
            dateTime
        }
    },
    created() {
        this.setup();
    },
    mounted() {
        console.log(`Component ${this.componentName} mounted.`);

        setInterval(() => {
            this.setupSlots();
        }, 5000);
    },
    methods: {
        async setup() {
            await this.setupSlots();
        },
        async setupSlots() {
            const slots = await this.fetchSlots();

            this.slots = slots || [];
            this.getDatetimeNow();

            // Data Loader Animation
            if (this.slots.length > 0) {
                document.getElementById('data-loader').style.display = 'none';
            } else {
                document.getElementById('data-loader').style.display = 'block';
            }
        },
        async fetchSlots() {
            let result = await axios.get(`http://${this.appUrl}/slots`)
                .catch((error) => {
                    // handle error
                    console.log(error);
                })
            return result.data.data;
        },
        getDatetimeNow() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            var hour = today.getHours();
            var minute = today.getMinutes();
            var second = today.getSeconds();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }

            this.dateTime = `${dd}/${mm}/${yyyy} ${hour}:${minute}:${second}`;
        }
    }
}
</script>

<style>
    html, body {
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>
