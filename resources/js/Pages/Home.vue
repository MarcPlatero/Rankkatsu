<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import AdBanner from '@/Components/AdBanner.vue'
import PixelAvatar from '@/Components/PixelAvatar.vue'

const props = defineProps({ 
  trendingRankings: Array,
  topRankings: Array, 
  latestRankings: Array 
})

const page = usePage()
const isPremium = page.props.auth.user?.is_premium

const activeTab = ref('popular') 

const top3Popular = computed(() => {
  return props.topRankings ? props.topRankings.slice(0, 3) : []
})

const currentList = computed(() => {
  if (activeTab.value === 'trending') return props.trendingRankings;
  if (activeTab.value === 'popular') return props.topRankings ? props.topRankings.slice(3) : [];
  if (activeTab.value === 'latest') return props.latestRankings;
  return [];
})

const isPixelAvatar = (path) => {
  return path && typeof path === 'string' && path.startsWith('pixel-')
}

const timeAgo = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    let interval = Math.floor(seconds / 31536000);
    if (interval >= 1) return `Fa ${interval} ${interval === 1 ? 'any' : 'anys'}`;
    
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) return `Fa ${interval} ${interval === 1 ? 'mes' : 'mesos'}`;
    
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) return `Fa ${interval} ${interval === 1 ? 'dia' : 'dies'}`;
    
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) return `Fa ${interval} ${interval === 1 ? 'hora' : 'hores'}`;
    
    interval = Math.floor(seconds / 60);
    if (interval >= 1) return `Fa ${interval} ${interval === 1 ? 'minut' : 'minuts'}`;
    
    return 'Ara mateix';
}

const showContent = ref(false)

onMounted(() => {
  showContent.value = true
})
</script>

<style scoped>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob { animation: blob 10s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}
.animate-float { animation: float 6s ease-in-out infinite; }

@keyframes shine {
  0% { background-position: 200% center; }
  100% { background-position: -200% center; }
}
.animate-shine {
  background-size: 200% auto;
  animation: shine 3s linear infinite reverse;
}

@keyframes pulse-slow {
  0%, 100% { opacity: 0.2; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(1.05); }
}
.animate-pulse-slow { animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
.animation-delay-1000 { animation-delay: 1s; }

.fade-up-enter-active, .fade-up-leave-active { transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
.fade-up-enter-from, .fade-up-leave-to { opacity: 0; transform: translateY(30px); }

.bg-grid-pattern {
  background-image: radial-gradient(rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 40px 40px;
}
.dark .bg-grid-pattern {
  background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
}

.title-clamp {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3em;
  height: 2.6em;
}

@keyframes podi-enter {
    from { opacity: 0; transform: translateY(50px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-podi { animation: podi-enter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
.delay-100 { animation-delay: 100ms; }
.delay-200 { animation-delay: 200ms; }
.delay-300 { animation-delay: 300ms; }
</style>

<template>
  <AppLayout>
    <Head title="Rankkatsu - Home" />

    <div class="w-full min-h-screen flex flex-col bg-gray-50 dark:bg-gradient-to-b dark:from-gray-950 dark:via-gray-950 dark:via-90% dark:to-black transition-colors duration-500 -mb-12 pb-24">

        <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
          
          <div class="absolute inset-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute inset-0 bg-grid-pattern opacity-[0.3] z-0"></div>
            <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 dark:bg-purple-900/40 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-blue-300 dark:bg-blue-900/40 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 dark:bg-pink-900/40 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/60 to-gray-50 dark:via-gray-950/60 dark:to-gray-950 backdrop-blur-[2px]"></div>
          </div>

          <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col items-center">
            <Transition name="fade-up" appear>
              <div v-show="showContent" class="w-full flex flex-col items-center text-center">
                
                <div class="mb-12 w-full max-w-2xl animate-float drop-shadow-xl filter hover:drop-shadow-[0_0_20px_rgba(59,130,246,0.4)] transition-all duration-500">
                    <svg viewBox="0 0 6508 1417" class="w-full h-auto text-gray-900 dark:text-white fill-current" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2675.5 286.107C2664.73 285.849 2639.03 286.596 2618.39 287.767C2576.26 290.157 2543.75 290.385 2511.26 288.517C2468.88 286.08 2428.31 285.447 2422.19 287.128C2414.23 289.314 2411 292.852 2411.51 298.839C2412.18 306.617 2415.68 308.151 2438.02 310.437C2459.09 312.592 2468.73 315.388 2477.85 321.994C2484.42 326.753 2490.58 335.999 2493.34 345.23C2496.34 355.251 2499.03 382.181 2499.87 410.562C2500.29 424.495 2500.95 443.395 2501.36 452.562C2502.4 476.132 2502.39 804.9 2501.35 845.896C2498.69 950.374 2494.62 978.143 2480.11 990.886C2473.29 996.873 2463.44 999.992 2443.26 1002.56C2420.04 1005.51 2416.59 1007.26 2416.59 1016.07C2416.59 1020.06 2422.16 1025.53 2427.03 1026.31C2433.96 1027.42 2482.93 1026.76 2506.59 1025.25C2542.58 1022.95 2574.51 1022.93 2615.26 1025.18C2658.51 1027.58 2689.78 1027.67 2697.22 1025.43C2706.97 1022.49 2708.75 1012.39 2700.42 1007.32C2697.87 1005.77 2691.2 1004.22 2682.79 1003.22C2654.47 999.864 2642.85 996.54 2634.32 989.361C2626.08 982.429 2621.8 963.893 2619.33 924.562C2617.55 896.328 2614.5 682.865 2615.78 676.146C2616.49 672.378 2617.32 671.174 2618.98 671.493C2620.21 671.73 2648.53 708.297 2681.9 752.751C2747.97 840.74 2754.53 849.416 2779.08 881.208C2788.14 892.953 2804.58 914.563 2815.61 929.23C2842.88 965.515 2855.02 979.38 2870.26 991.648C2885.69 1004.06 2904.04 1014.22 2919.92 1019.15C2941.55 1025.86 2947.04 1026.19 3046.59 1026.86C3164.44 1027.65 3181.52 1026.4 3210.23 1014.82C3240.7 1002.53 3267.44 977.339 3306.02 924.562C3312.19 916.128 3322.8 902.03 3329.61 893.23C3336.42 884.43 3351.58 864.63 3363.28 849.23C3374.99 833.83 3389.17 815.229 3394.8 807.896C3417.26 778.619 3433.95 756.312 3447.18 737.896C3461 718.641 3496.3 672.562 3497.22 672.562C3497.49 672.562 3498.75 672.163 3500.02 671.675C3502.21 670.836 3502.28 675.732 3501.43 761.341C3499.59 945.669 3497.13 973.548 3481.37 989.431C3474.44 996.408 3464.26 999.606 3439.26 1002.67C3429.36 1003.88 3419.58 1005.73 3417.53 1006.78C3409.74 1010.77 3409.43 1020.12 3416.94 1024.18C3422.7 1027.29 3450.04 1028 3484 1025.92C3530.39 1023.08 3569.59 1022.82 3608.96 1025.09C3659.84 1028.03 3687.78 1027.96 3693.92 1024.89C3700.04 1021.84 3702.72 1016.38 3700.62 1011.3C3698.49 1006.15 3694.79 1004.82 3678.25 1003.24C3648.49 1000.4 3636.72 994.357 3629.55 978.245C3622.49 962.398 3619.62 940.572 3617.92 889.724C3616.19 837.887 3615.41 602.682 3616.64 501.896C3618.34 362.143 3621.11 339.265 3638.17 323.925C3647.95 315.131 3658.54 312.206 3691.54 309.181C3704.84 307.962 3709.96 302.495 3706.43 293.288C3703.55 285.787 3683.25 284.545 3618.59 287.913C3571.08 290.389 3533.84 290.169 3485.26 287.128C3472.06 286.302 3452.74 285.844 3442.33 286.111C3425.79 286.534 3422.95 286.94 3419.75 289.327C3414.61 293.167 3413.08 298.412 3415.87 302.665C3418.77 307.095 3422.87 308.506 3437.26 310.038C3454.45 311.87 3463.08 313.778 3469.92 317.265C3481.49 323.158 3489.14 333.647 3493.17 349.124C3497.45 365.535 3499.8 428.265 3501.47 570.394L3502.36 646.226L3499.64 644.769C3497.04 643.374 3450.85 595.947 3434.52 577.896C3430.21 573.129 3413.12 554.528 3396.55 536.562C3339.73 474.97 3269.77 397.512 3260.85 386.327C3243.1 364.082 3233.92 345.455 3233.92 331.689C3233.92 320.448 3237.02 316.416 3250.65 309.941C3262.51 304.31 3264.51 301.755 3262.72 294.587C3261.06 287.972 3255.49 286.568 3231.92 286.822C3220.19 286.948 3199.49 287.773 3185.92 288.656C3168.66 289.779 3152.26 289.905 3131.26 289.077C3097.89 287.762 3044.07 287.577 2984.59 288.572C2962.96 288.933 2933.56 288.629 2919.26 287.896C2868.39 285.287 2858.37 286.384 2855.12 294.921C2852.81 300.997 2855.42 304.043 2867.85 309.786C2882.69 316.645 2884.98 319.975 2884.2 333.603C2883.74 341.66 2882.71 345.218 2878.1 354.519C2869.21 372.465 2857.44 387.445 2822.56 425.23C2814.43 434.03 2798.86 451.13 2787.95 463.23C2777.05 475.33 2761.48 492.419 2753.36 501.206C2745.23 509.994 2735.87 520.195 2732.54 523.874C2695.63 564.657 2682.83 578.612 2659.73 603.23C2624.87 640.377 2618.6 646.47 2616.52 645.187C2613.81 643.51 2617.14 426.879 2620.57 381.896C2623.35 345.34 2628.81 329.114 2641.06 320.908C2649.47 315.283 2657.57 312.967 2677.92 310.37C2687.09 309.201 2696.23 307.404 2698.24 306.378C2704.68 303.085 2705 293.957 2698.83 289.37C2695.5 286.894 2692.86 286.523 2675.5 286.107ZM3059.61 313.677L3069.77 317.279C3090.36 324.583 3112.86 337.364 3131.26 352.216C3142.15 361.007 3165.18 383.167 3176.68 395.923C3180.67 400.338 3199.22 420.138 3217.92 439.923C3236.61 459.709 3256.37 480.695 3261.82 486.562C3267.28 492.428 3276 501.73 3281.21 507.23C3286.42 512.73 3295.73 522.63 3301.9 529.23C3308.08 535.83 3317.66 546.051 3323.19 551.945C3328.73 557.839 3339.86 569.83 3347.92 578.591C3355.99 587.353 3367.69 599.931 3373.93 606.542C3380.17 613.153 3386.59 620.412 3388.2 622.673L3391.12 626.785L3387.38 631.341C3385.32 633.847 3376.77 644.896 3368.38 655.896C3343.24 688.86 3278.82 771.218 3236.45 824.562C3224.51 839.595 3206.92 862.095 3197.37 874.562C3143.39 944.995 3109.99 976.835 3072.34 993.773C3059.45 999.57 3059.49 999.569 3048.17 994.64C3025.63 984.818 3003.94 969.009 2979.15 944.32C2959.46 924.719 2954.33 918.56 2914.95 867.23C2897.14 844.014 2880.96 823.353 2855.94 791.896C2844.85 777.963 2828.87 757.863 2820.42 747.23C2804.17 726.776 2772.9 686.49 2747.47 653.23C2739.06 642.23 2730.65 631.571 2728.78 629.544L2725.39 625.861L2731.38 619.544C2737.58 613.012 2778.84 569.323 2796.53 550.562C2802.06 544.695 2810.49 535.674 2815.26 530.513C2828.35 516.335 2858.34 484.296 2872.59 469.255C2879.56 461.903 2896.63 443.591 2910.53 428.56C2941.86 394.685 2976 360.511 2986.59 352.437C3010.18 334.446 3032.84 321.662 3050.43 316.413L3059.61 313.677Z" fill="currentColor"/>
                        <path d="M3043.26 810.248C3040.69 806.82 3035.1 800.268 3030.83 795.688L3023.07 787.36L3031.5 783.139C3058.45 769.636 3080.37 746.972 3086.54 726.229L3087.44 723.229H3068.34H3049.24L3049.72 712.563C3049.99 706.696 3049.83 701.896 3049.38 701.896C3048.92 701.896 3045.24 704.596 3041.19 707.896C3037.13 711.196 3033.62 713.896 3033.37 713.896C3033.12 713.896 3029.39 709.246 3025.08 703.563L3017.26 693.229L3016.59 743.229C3015.98 788.578 3015.69 793.696 3013.43 798.245C3007.97 809.238 2997.77 813.659 2975.13 814.846L2961.01 815.587L2959.35 807.075C2958.43 802.393 2955.94 794.063 2953.81 788.563C2951.68 783.063 2949.93 778.263 2949.93 777.896C2949.92 777.529 2955.73 777.441 2962.84 777.7C2974.06 778.109 2975.99 777.856 2977.51 775.778C2978.85 773.943 2979.26 764.915 2979.26 736.974V700.563H2965.36H2951.47L2950.61 709.563C2947.31 744.32 2941.57 774.954 2933.99 798.229C2930.94 807.579 2928.2 815.229 2927.9 815.229C2927.59 815.229 2923.75 812.906 2919.36 810.066C2914.96 807.227 2907.43 803.188 2902.62 801.092C2895.96 798.19 2894.09 796.798 2894.8 795.255C2901.41 780.74 2908.86 747.249 2911.92 718.282C2914.82 690.844 2915.49 667.155 2915.71 583.896L2915.92 506.563L2966.26 506.213L3016.59 505.864V591.927V677.99L3019.59 676.4C3023.59 674.278 3051.26 646.596 3051.26 644.711C3051.26 643.718 3046.63 643.229 3037.26 643.229H3023.26V626.593V609.956L3047.59 609.593L3071.92 609.229L3076.28 598.147C3078.68 592.051 3080.3 586.501 3079.87 585.813C3079.44 585.114 3069.14 584.563 3056.51 584.563H3033.92V568.563V552.563H3041.92C3046.32 552.563 3049.92 551.98 3049.92 551.268C3049.92 548.312 3041.66 527.635 3037.33 519.754L3032.66 511.262L3036.29 509.408C3042.59 506.19 3063.6 500.308 3064.45 501.525C3064.89 502.166 3067.27 505.558 3069.72 509.062C3074.65 516.089 3084.59 536.492 3084.59 539.581C3084.59 540.72 3080.66 543.296 3075.19 545.741C3070.02 548.052 3066.12 550.469 3066.52 551.112C3067.14 552.117 3091.25 553.226 3091.26 552.25C3091.26 552.055 3092.72 544.396 3094.49 535.229C3096.27 526.063 3098.09 514.363 3098.54 509.229C3100.16 490.944 3100 491.316 3105.63 492.105C3138.11 496.65 3150.28 499.528 3145.11 501.444C3140.37 503.203 3139.3 506.124 3135.22 528.312C3132.89 541.008 3131.26 551.676 3131.6 552.02C3131.95 552.364 3139.96 552.476 3149.41 552.27L3166.59 551.896L3161.51 549.277C3158.09 547.513 3156.65 545.99 3157.12 544.61C3160.4 534.848 3175.35 499.865 3176.39 499.518C3177.51 499.144 3186.79 501.781 3213.59 510.092C3219.14 511.815 3219.07 513.381 3213.32 515.784C3209.54 517.364 3207.72 519.677 3203.18 528.715C3200.14 534.768 3196.21 541.999 3194.45 544.782C3192.69 547.566 3191.26 550.455 3191.26 551.203C3191.26 552.054 3197.48 552.563 3207.92 552.563H3224.59V568.563V584.563H3198.59C3178.66 584.563 3172.6 584.952 3172.62 586.229C3172.64 587.146 3174.59 592.696 3176.95 598.563L3181.26 609.229L3207.26 609.969C3221.56 610.376 3233.47 610.826 3233.72 610.969C3233.98 611.112 3233.95 618.279 3233.66 626.896L3233.13 642.563L3217.52 642.938C3208.64 643.152 3201.92 643.868 3201.92 644.601C3201.92 648.196 3230.21 673.883 3239.4 678.638L3243.5 680.756L3235.56 687.942C3231.19 691.894 3225.28 698.746 3222.44 703.167C3219.59 707.588 3216.89 711.211 3216.45 711.218C3216 711.228 3212.89 709.129 3209.53 706.563C3206.17 703.996 3203.16 701.896 3202.86 701.896C3202.55 701.896 3201.88 709.846 3201.38 719.563C3197.45 794.944 3191.72 808.848 3162.59 813.832C3157.46 814.71 3146.36 815.234 3137.92 814.995L3122.59 814.563L3121.45 809.229C3120.83 806.296 3118.88 799.008 3117.12 793.034C3115.36 787.06 3113.92 781.823 3113.92 781.395C3113.92 780.968 3121.27 780.979 3130.26 781.42C3145.65 782.176 3146.8 782.055 3150.21 779.332C3152.2 777.742 3154.46 774.219 3155.23 771.502C3157.41 763.796 3160.38 743.52 3160.84 733.23L3161.26 723.896L3144.23 723.523C3131.29 723.239 3127.08 723.559 3126.71 724.856C3115.48 763.516 3102.82 782.231 3074.32 802.256C3068.63 806.259 3049.63 816.672 3048.29 816.522C3048.09 816.499 3045.82 813.676 3043.26 810.248ZM3095.76 685.729C3096.21 683.438 3096.59 676.838 3096.59 671.063V660.563L3102.26 660.574C3111.02 660.592 3138.08 663.165 3139.64 664.131C3140.47 664.644 3139.63 666.123 3137.58 667.778C3134.65 670.136 3133.98 672.041 3133.18 680.229L3132.25 689.896H3160.46C3188 689.896 3188.65 689.833 3187.3 687.303C3186.53 685.877 3185.26 684.309 3184.46 683.818C3182.06 682.333 3167.53 662.162 3161.82 652.392L3156.47 643.229H3126.66H3096.84L3092.86 650.296C3087.79 659.274 3074.27 677.173 3068.04 683.157C3065.41 685.684 3063.26 688.234 3063.26 688.824C3063.26 689.413 3070.38 689.896 3079.09 689.896H3094.92L3095.76 685.729ZM2978.96 642.749L2979.32 621.896H2965.96H2952.59V642.34C2952.59 653.585 2952.9 663.093 2953.27 663.47C2953.65 663.846 2959.5 664.03 2966.27 663.879L2978.59 663.603L2978.96 642.749ZM3141.92 608.836C3141.92 608.252 3140.22 602.702 3138.13 596.502L3134.35 585.229H3127.98H3121.61L3117.77 596.562C3115.65 602.795 3113.92 608.345 3113.92 608.895C3113.92 609.446 3120.22 609.896 3127.92 609.896C3135.62 609.896 3141.92 609.419 3141.92 608.836ZM2978.59 563.896V542.563L2965.59 542.181L2952.59 541.799V562.959C2952.59 574.596 2953.01 584.539 2953.52 585.054C2954.04 585.568 2959.89 585.818 2966.52 585.609L2978.59 585.229V563.896Z" fill="#AA0000"/>
                        <path d="M236.25 290H378C409.5 290 439.25 294.2 467.25 302.6C495.25 310.3 519.4 322.2 539.7 338.3C560 354.4 576.1 375.05 588 400.25C599.9 424.75 605.85 453.8 605.85 487.4C605.85 518.9 598.85 549.7 584.85 579.8C570.85 609.9 550.9 635.1 525 655.4C499.1 675 467.6 685.85 430.5 687.95C448 694.25 464.8 705.8 480.9 722.6C497.7 738.7 511.7 754.8 522.9 770.9C523.6 771.6 527.8 778.25 535.5 790.85C543.9 803.45 554.4 818.85 567 837.05C579.6 854.55 592.55 872.4 605.85 890.6C619.15 908.1 630.7 922.8 640.5 934.7C655.2 952.2 669.2 966.9 682.5 978.8C695.8 990 710.15 998.75 725.55 1005.05C740.95 1011.35 758.1 1014.5 777 1014.5V1025H696.15C661.15 1025 630 1020.8 602.7 1012.4C575.4 1004 551.6 992.45 531.3 977.75C511.7 962.35 494.55 944.5 479.85 924.2C476.35 917.9 470.4 908.45 462 895.85C454.3 882.55 445.2 867.85 434.7 851.75C424.9 834.95 415.1 818.85 405.3 803.45C396.2 787.35 388.15 773.35 381.15 761.45C374.15 748.85 369.95 740.45 368.55 736.25C357.35 716.65 344.4 700.55 329.7 687.95C315.7 674.65 300.65 667.3 284.55 665.9V655.4C285.95 655.4 290.85 655.75 299.25 656.45C307.65 656.45 317.45 656.1 328.65 655.4C346.85 654.7 365.75 651.55 385.35 645.95C405.65 639.65 423.85 628.1 439.95 611.3C456.05 594.5 467.95 570.7 475.65 539.9C477.75 532.9 479.15 524.5 479.85 514.7C481.25 504.2 481.6 493.7 480.9 483.2C479.5 434.2 467.25 396.75 444.15 370.85C421.05 344.95 390.95 331.3 353.85 329.9C339.85 329.2 324.8 329.2 308.7 329.9C292.6 329.9 278.6 329.9 266.7 329.9C255.5 329.9 248.85 329.9 246.75 329.9C246.75 329.2 245.7 325.7 243.6 319.4C242.2 312.4 240.45 305.75 238.35 299.45C236.95 293.15 236.25 290 236.25 290ZM249.9 290V1025H135.45V290H249.9ZM138.6 948.35V1025H54.6V1014.5C55.3 1014.5 57.75 1014.5 61.95 1014.5C66.15 1014.5 68.25 1014.5 68.25 1014.5C86.45 1014.5 101.85 1008.2 114.45 995.6C127.75 982.3 134.75 966.55 135.45 948.35H138.6ZM138.6 366.65H135.45C134.75 348.45 127.75 333.05 114.45 320.45C101.85 307.15 86.45 300.5 68.25 300.5C68.25 300.5 66.15 300.5 61.95 300.5C57.75 300.5 55.3 300.5 54.6 300.5V290H138.6V366.65ZM246.75 948.35H249.9C249.9 966.55 256.55 982.3 269.85 995.6C283.15 1008.2 298.9 1014.5 317.1 1014.5C317.8 1014.5 319.9 1014.5 323.4 1014.5C327.6 1014.5 329.7 1014.5 329.7 1014.5V1025H246.75V948.35ZM1100.8 272.15L1428.4 1018.7H1288.75L1057.75 424.4L1100.8 272.15ZM875.052 948.35C870.152 962.35 869.452 974.25 872.952 984.05C876.452 993.85 882.052 1001.55 889.752 1007.15C898.152 1012.05 906.552 1014.5 914.952 1014.5H924.402V1025H707.052V1014.5C707.052 1014.5 708.802 1014.5 712.302 1014.5C715.102 1014.5 716.502 1014.5 716.502 1014.5C732.602 1014.5 749.052 1009.6 765.852 999.8C783.352 989.3 797.702 972.15 808.902 948.35H875.052ZM1100.8 272.15L1108.15 374L845.652 1021.85H776.352L1046.2 395C1047.6 392.9 1050.4 386.95 1054.6 377.15C1059.5 366.65 1064.75 354.75 1070.35 341.45C1075.95 327.45 1080.85 314.15 1085.05 301.55C1089.25 288.25 1091.35 278.45 1091.35 272.15H1100.8ZM1229.95 757.25V795.05H910.752V757.25H1229.95ZM1260.4 948.35H1397.95C1408.45 972.15 1422.45 989.3 1439.95 999.8C1457.45 1009.6 1474.25 1014.5 1490.35 1014.5C1490.35 1014.5 1491.75 1014.5 1494.55 1014.5C1497.35 1014.5 1498.75 1014.5 1498.75 1014.5V1025H1211.05V1014.5H1220.5C1234.5 1014.5 1246.75 1008.9 1257.25 997.7C1267.75 985.8 1268.8 969.35 1260.4 948.35ZM1600.44 274.25L2240.94 918.95L2252.49 1039.7L1610.94 396.05L1600.44 274.25ZM1603.59 950.45V1025H1516.44V1014.5C1516.44 1014.5 1519.59 1014.5 1525.89 1014.5C1532.19 1014.5 1535.69 1014.5 1536.39 1014.5C1553.89 1014.5 1568.94 1008.2 1581.54 995.6C1594.14 983 1600.44 967.95 1600.44 950.45H1603.59ZM1701.24 950.45C1701.24 967.95 1707.54 983 1720.14 995.6C1732.74 1008.2 1747.79 1014.5 1765.29 1014.5C1765.99 1014.5 1769.49 1014.5 1775.79 1014.5C1782.09 1014.5 1785.24 1014.5 1785.24 1014.5V1025H1698.09V950.45H1701.24ZM1600.44 274.25L1700.19 385.55L1701.24 1025H1600.44V405.5C1600.44 367.7 1598.69 336.55 1595.19 312.05C1591.69 286.85 1589.94 274.25 1589.94 274.25H1600.44ZM2250.39 290V905.3C2250.39 929.8 2251.09 952.55 2252.49 973.55C2254.59 993.85 2256.69 1009.95 2258.79 1021.85C2260.89 1033.75 2261.94 1039.7 2261.94 1039.7H2252.49L2150.64 925.25V290H2250.39ZM2334.39 290V300.5C2334.39 300.5 2331.24 300.5 2324.94 300.5C2318.64 300.5 2315.49 300.5 2315.49 300.5C2297.99 300.5 2282.94 306.8 2270.34 319.4C2257.74 331.3 2251.09 346.35 2250.39 364.55H2247.24V290H2334.39ZM2065.59 290H2152.74V364.55H2150.64C2149.94 346.35 2143.29 331.3 2130.69 319.4C2118.79 306.8 2103.74 300.5 2085.54 300.5C2085.54 300.5 2082.39 300.5 2076.09 300.5C2069.79 300.5 2066.29 300.5 2065.59 300.5V290Z" fill="currentColor"/>
                        <path d="M4147.8 274.15L4475.4 1020.7H4335.75L4104.75 426.4L4147.8 274.15ZM3922.05 950.35C3917.15 964.35 3916.45 976.25 3919.95 986.05C3923.45 995.85 3929.05 1003.55 3936.75 1009.15C3945.15 1014.05 3953.55 1016.5 3961.95 1016.5H3971.4V1027H3754.05V1016.5C3754.05 1016.5 3755.8 1016.5 3759.3 1016.5C3762.1 1016.5 3763.5 1016.5 3763.5 1016.5C3779.6 1016.5 3796.05 1011.6 3812.85 1001.8C3830.35 991.3 3844.7 974.15 3855.9 950.35H3922.05ZM4147.8 274.15L4155.15 376L3892.65 1023.85H3823.35L4093.2 397C4094.6 394.9 4097.4 388.95 4101.6 379.15C4106.5 368.65 4111.75 356.75 4117.35 343.45C4122.95 329.45 4127.85 316.15 4132.05 303.55C4136.25 290.25 4138.35 280.45 4138.35 274.15H4147.8ZM4276.95 759.25V797.05H3957.75V759.25H4276.95ZM4307.4 950.35H4444.95C4455.45 974.15 4469.45 991.3 4486.95 1001.8C4504.45 1011.6 4521.25 1016.5 4537.35 1016.5C4537.35 1016.5 4538.75 1016.5 4541.55 1016.5C4544.35 1016.5 4545.75 1016.5 4545.75 1016.5V1027H4258.05V1016.5H4267.5C4281.5 1016.5 4293.75 1010.9 4304.25 999.7C4314.75 987.8 4315.8 971.35 4307.4 950.35ZM4836.35 295.15V1027H4721.9V295.15H4836.35ZM5098.85 290.95V328.75H4458.35V290.95H5098.85ZM5098.85 325.6V430.6L5088.35 429.55V414.85C5088.35 389.65 5080.3 369.35 5064.2 353.95C5048.8 337.85 5028.5 329.45 5003.3 328.75V325.6H5098.85ZM5098.85 272.05V302.5L4970.75 290.95C4986.15 290.95 5002.25 289.9 5019.05 287.8C5036.55 285.7 5052.65 283.25 5067.35 280.45C5082.05 277.65 5092.55 274.85 5098.85 272.05ZM4725.05 950.35V1027H4641.05V1016.5C4641.05 1016.5 4643.15 1016.5 4647.35 1016.5C4652.25 1016.5 4654.7 1016.5 4654.7 1016.5C4672.9 1016.5 4688.3 1010.2 4700.9 997.6C4714.2 984.3 4721.2 968.55 4721.9 950.35H4725.05ZM4833.2 950.35H4836.35C4837.05 968.55 4843.7 984.3 4856.3 997.6C4869.6 1010.2 4885.35 1016.5 4903.55 1016.5C4903.55 1016.5 4905.65 1016.5 4909.85 1016.5C4914.75 1016.5 4917.2 1016.5 4917.2 1016.5V1027H4833.2V950.35ZM4553.9 325.6V328.75C4528.7 329.45 4508.05 337.85 4491.95 353.95C4476.55 369.35 4468.85 389.65 4468.85 414.85V429.55L4458.35 430.6V325.6H4553.9ZM4458.35 272.05C4465.35 274.85 4475.85 277.65 4489.85 280.45C4504.55 283.25 4520.65 285.7 4538.15 287.8C4555.65 289.9 4571.75 290.95 4586.45 290.95L4458.35 302.5V272.05ZM5392.41 277.3C5405.01 277.3 5419.71 278 5436.51 279.4C5454.01 280.8 5471.16 282.9 5487.96 285.7C5505.46 287.8 5520.86 290.25 5534.16 293.05C5548.16 295.15 5557.96 297.6 5563.56 300.4L5557.26 430.6H5547.81C5547.81 394.9 5535.21 366.9 5510.01 346.6C5484.81 325.6 5452.61 315.1 5413.41 315.1C5373.51 315.1 5341.31 325.95 5316.81 347.65C5293.01 369.35 5280.76 395.6 5280.06 426.4C5279.36 443.2 5283.91 460 5293.71 476.8C5304.21 492.9 5318.91 508.3 5337.81 523L5541.51 682.6C5568.11 701.5 5587.36 723.9 5599.26 749.8C5611.16 775.7 5616.76 804.05 5616.06 834.85C5613.96 897.85 5591.56 948.25 5548.86 986.05C5506.86 1023.15 5449.81 1041.7 5377.71 1041.7C5353.21 1041.7 5328.01 1039.25 5302.11 1034.35C5276.21 1030.15 5252.06 1023.5 5229.66 1014.4C5207.96 1005.3 5190.46 994.1 5177.16 980.8C5172.96 967.5 5171.21 951.4 5171.91 932.5C5172.61 912.9 5175.06 892.95 5179.26 872.65C5183.46 851.65 5189.06 833.45 5196.06 818.05H5205.51C5202.71 853.75 5208.31 885.95 5222.31 914.65C5236.31 943.35 5256.96 965.75 5284.26 981.85C5312.26 997.25 5344.46 1004.6 5380.86 1003.9C5422.86 1002.5 5457.16 989.9 5483.76 966.1C5510.36 942.3 5523.66 911.85 5523.66 874.75C5523.66 853.75 5518.41 834.85 5507.91 818.05C5498.11 801.25 5482.36 785.85 5460.66 771.85L5268.51 618.55C5239.11 597.55 5218.11 573.05 5205.51 545.05C5192.91 516.35 5187.66 487.3 5189.76 457.9C5191.16 425 5199.91 394.9 5216.01 367.6C5232.11 339.6 5254.86 317.55 5284.26 301.45C5314.36 285.35 5350.41 277.3 5392.41 277.3ZM5564.61 287.8L5563.56 309.85H5460.66V287.8H5564.61ZM5896.89 292V754C5896.89 803.7 5905.64 847.45 5923.14 885.25C5940.64 922.35 5965.14 951.4 5996.64 972.4C6028.84 992.7 6066.29 1002.85 6108.99 1002.85C6153.79 1002.85 6192.64 993.05 6225.54 973.45C6258.44 953.85 6283.99 926.55 6302.19 891.55C6321.09 856.55 6330.54 815.25 6330.54 767.65V292H6392.49V764.5C6392.49 820.5 6380.59 869.15 6356.79 910.45C6332.99 951.75 6299.04 983.95 6254.94 1007.05C6211.54 1030.15 6159.74 1041.7 6099.54 1041.7C6033.04 1041.7 5975.99 1030.5 5928.39 1008.1C5880.79 985 5844.39 952.8 5819.19 911.5C5794.69 870.2 5782.44 821.55 5782.44 765.55V292H5896.89ZM5785.59 292V355H5782.44C5782.44 339.6 5776.84 327 5765.64 317.2C5755.14 307.4 5742.19 302.5 5726.79 302.5C5726.79 302.5 5723.99 302.5 5718.39 302.5C5712.79 302.5 5709.99 302.5 5709.99 302.5V292H5785.59ZM5969.34 292V302.5C5969.34 302.5 5966.54 302.5 5960.94 302.5C5955.34 302.5 5952.54 302.5 5952.54 302.5C5937.14 302.5 5923.84 307.4 5912.64 317.2C5902.14 327 5896.89 339.6 5896.89 355H5894.79V292H5969.34ZM6333.69 292V355H6330.54C6330.54 339.6 6324.94 327 6313.74 317.2C6303.24 307.4 6290.29 302.5 6274.89 302.5C6274.89 302.5 6272.09 302.5 6266.49 302.5C6260.89 302.5 6258.09 302.5 6258.09 302.5V292H6333.69ZM6464.94 292V302.5C6464.94 302.5 6462.14 302.5 6456.54 302.5C6450.94 302.5 6448.14 302.5 6448.14 302.5C6432.74 302.5 6419.44 307.4 6408.24 317.2C6397.74 327 6392.49 339.6 6392.49 355H6390.39V292H6464.94Z" fill="currentColor"/>
                    </svg>
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-12 leading-tight text-center drop-shadow-sm flex flex-col items-center">
                  <span class="relative inline-block z-10 mb-2 md:mb-0">
                      <span class="absolute -inset-4 rounded-full bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 dark:from-red-600 dark:via-orange-500 dark:to-rose-600 opacity-20 blur-2xl animate-pulse-slow -z-10"></span>
                      
                      <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 dark:from-red-400 dark:via-orange-300 dark:to-rose-400">
                        Crea, Vota i
                      </span>
                  </span>

                  <span class="relative inline-block mt-1 z-10">
                    <span class="absolute -inset-4 rounded-full bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 dark:from-red-600 dark:via-orange-500 dark:to-rose-600 opacity-25 blur-2xl animate-pulse-slow -z-10 animation-delay-1000"></span>

                    <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 dark:from-red-400 dark:via-orange-300 dark:to-rose-400">
                      Decideix el Millor!
                    </span>
                    
                    <svg class="absolute -bottom-3 left-0 w-full h-4 text-blue-500/40 dark:text-red-500/50 z-0" viewBox="0 0 300 16" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                      <path d="M3 12.5C48.5 3.5 145.5 -2.5 297 12.5" stroke="currentColor" stroke-width="5" stroke-linecap="round" vector-effect="non-scaling-stroke"/>
                    </svg>
                  </span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-12 max-w-2xl mx-auto text-center font-medium leading-relaxed">
                  La plataforma definitiva per crear rànquings. Descobreix què pensa la comunitat i ajuda a coronar al guanyador.
                </p>

                <div class="flex flex-col sm:flex-row justify-center items-center gap-5 w-full px-4">
                  
                  <Link
                    href="/rankings/create"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-2xl p-4 px-10 font-bold text-white transition-all duration-300 hover:scale-105 shadow-xl hover:shadow-blue-500/40"
                  >
                    <span class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700 transition-all duration-300 group-hover:from-blue-500 group-hover:to-indigo-600"></span>
                    <span class="absolute inset-0 opacity-0 group-hover:opacity-20 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.6)_50%,transparent_75%,transparent_100%)] bg-[length:250%_250%] animate-shine"></span>
                    <span class="relative flex items-center gap-3 text-lg leading-none">
                      <span class="text-2xl">🚀</span> Crear Ranking
                    </span>
                  </Link>

                  <Link
                    v-if="!isPremium"
                    href="/premium"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-2xl p-4 px-10 font-bold text-white transition-all duration-300 hover:scale-105 shadow-xl hover:shadow-amber-500/40"
                  >
                    <span class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 transition-all duration-300 group-hover:from-amber-400 group-hover:to-orange-500"></span>
                    <span class="absolute inset-0 opacity-0 group-hover:opacity-25 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.7)_50%,transparent_75%,transparent_100%)] bg-[length:250%_250%] animate-shine"></span>
                    <span class="relative flex items-center gap-3 text-lg leading-none">
                      <span class="text-2xl">👑</span> Fes-te Premium
                    </span>
                  </Link>
                  
                  <Link
                    href="/rankings"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-2xl p-4 px-10 font-bold text-white transition-all duration-300 hover:scale-105 shadow-xl hover:shadow-red-600/40"
                  >
                    <span class="absolute inset-0 bg-gradient-to-br from-red-600 to-rose-700 transition-all duration-300 group-hover:from-red-500 group-hover:to-rose-600"></span>
                    <span class="absolute inset-0 opacity-0 group-hover:opacity-20 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.6)_50%,transparent_75%,transparent_100%)] bg-[length:250%_250%] animate-shine"></span>
                    <span class="relative flex items-center gap-3 text-lg leading-none">
                      <span class="text-2xl">🔍</span> Explorar Tot
                    </span>
                  </Link>
                </div>
              </div>
            </Transition>
          </div>
        </section>

        <div class="w-full max-w-7xl mx-auto my-8">
          <AdBanner format="horizontal" class="w-full" />
        </div>

        <section class="max-w-7xl mx-auto px-6 pt-6 pb-6">
          
          <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            
            <div class="flex p-1 space-x-1 bg-gray-100 dark:bg-gray-800 rounded-xl">
                <button
                    @click="activeTab = 'trending'"
                    :class="[
                        'px-6 py-2.5 text-sm font-bold rounded-lg transition-all duration-200',
                        activeTab === 'trending' 
                            ? 'bg-white dark:bg-gray-700 text-red-600 dark:text-red-400 shadow-sm' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🔥 Tendència
                </button>
                <button
                    @click="activeTab = 'popular'"
                    :class="[
                        'px-6 py-2.5 text-sm font-bold rounded-lg transition-all duration-200',
                        activeTab === 'popular' 
                            ? 'bg-white dark:bg-gray-700 text-yellow-600 dark:text-yellow-400 shadow-sm' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🏆 Més Populars
                </button>
                <button
                    @click="activeTab = 'latest'"
                    :class="[
                        'px-6 py-2.5 text-sm font-bold rounded-lg transition-all duration-200',
                        activeTab === 'latest' 
                            ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' 
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                    ]"
                >
                    🆕 Novetats
                </button>
            </div>

            <Link href="/rankings" class="font-medium text-sm transition-colors flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-red-500 dark:hover:text-red-400">
              Veure tots els rànquings &rarr;
            </Link>
          </div>

          <div v-if="activeTab === 'popular' && top3Popular.length > 0" class="mb-24 mt-16 px-4">
            
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-64 bg-blue-500/10 dark:bg-blue-900/10 blur-[100px] rounded-full -z-10 pointer-events-none"></div>

            <div class="flex flex-col md:flex-row justify-center items-end gap-6 md:gap-4 lg:gap-8 max-w-5xl mx-auto">
                
                <div v-if="top3Popular[1]" class="order-2 md:order-1 w-full md:w-[30%] relative group animate-podi delay-100 opacity-0">
                    <div class="absolute -inset-0.5 bg-gradient-to-b from-gray-300 via-gray-100 to-gray-400 rounded-[2.5rem] opacity-0 group-hover:opacity-60 blur transition duration-500"></div>
                    
                    <Link :href="`/rankings/${top3Popular[1].id}`" class="relative block h-full bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border-2 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:-translate-y-2 transition-all duration-500 shadow-xl shadow-gray-400/20 dark:shadow-gray-900/20 group-hover:shadow-gray-400/40 flex flex-col">
                        <div class="absolute top-4 left-4 z-20 w-12 h-12 rounded-full flex items-center justify-center shadow-lg border-2 border-gray-700 group-hover:border-white bg-black text-white group-hover:text-gray-900 group-hover:bg-gradient-to-br group-hover:from-gray-100 group-hover:to-gray-300 transition-all duration-500">
                            <span class="text-xl font-black">2</span>
                        </div>

                        <div class="h-64 overflow-hidden relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent z-10"></div>
                            <img v-if="top3Popular[1].image" :src="`/storage/${top3Popular[1].image}`" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                            <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl">🥈</div>
                        </div>

                        <div class="p-5 relative z-20 flex flex-col flex-grow justify-between text-center">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight line-clamp-2 mb-3 h-[3.2em] flex items-center justify-center transition-colors duration-300 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                {{ top3Popular[1].title }}
                            </h3>
                            
                            <div class="flex items-center justify-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="w-10 h-10 rounded-full border-2 border-gray-200 dark:border-gray-600 shadow-sm overflow-hidden bg-white dark:bg-gray-700 group-hover:border-gray-400 transition-colors duration-300 flex-shrink-0">
                                    <PixelAvatar v-if="isPixelAvatar(top3Popular[1].user?.profile_photo_path)" :id="top3Popular[1].user.profile_photo_path" className="w-full h-full" />
                                    <img v-else-if="top3Popular[1].user?.profile_photo_url" :src="top3Popular[1].user.profile_photo_url" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-300 group-hover:text-gray-700 dark:group-hover:text-gray-200 truncate max-w-[140px]">
                                    {{ top3Popular[1].user?.name }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-if="top3Popular[0]" class="order-1 md:order-2 w-full md:w-[36%] relative group z-10 -mt-10 md:-mt-0 animate-podi opacity-0">
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 z-30 animate-float">
                        <svg class="w-16 h-16 drop-shadow-[0_0_15px_rgba(234,179,8,0.6)] text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.6 18.6 20 18 20H6C5.4 20 5 19.6 5 19V18H19V19Z" /></svg>
                    </div>

                    <div class="absolute -inset-1 bg-gradient-to-b from-yellow-300 via-amber-400 to-yellow-600 rounded-[2.5rem] opacity-0 group-hover:opacity-80 blur-md transition duration-500 animate-pulse-slow"></div>
                    
                    <Link :href="`/rankings/${top3Popular[0].id}`" class="relative block h-full bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border-2 border-yellow-200 dark:border-yellow-900/50 group-hover:border-yellow-400 hover:-translate-y-3 transition-all duration-500 shadow-2xl shadow-yellow-500/20 group-hover:shadow-yellow-500/40 flex flex-col">
                        
                        <div class="absolute top-4 left-4 z-20 w-14 h-14 rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.5)] border-2 border-gray-700 group-hover:border-yellow-200 bg-black text-white group-hover:bg-gradient-to-br group-hover:from-yellow-300 group-hover:to-amber-500 transition-all duration-500">
                            <span class="text-2xl font-black drop-shadow-md">1</span>
                        </div>

                        <div class="h-80 overflow-hidden relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent z-10"></div>
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-30 bg-gradient-to-tr from-transparent via-white to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000 z-20 pointer-events-none"></div>
                            <img v-if="top3Popular[0].image" :src="`/storage/${top3Popular[0].image}`" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                            <div v-else class="w-full h-full bg-yellow-100 dark:bg-gray-700 flex items-center justify-center text-6xl">👑</div>
                        </div>

                        <div class="p-6 relative z-20 flex flex-col flex-grow justify-between text-center">
                            <h3 class="font-black text-xl md:text-2xl text-center leading-tight mb-3 line-clamp-2 h-[2.5em] flex items-center justify-center text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-yellow-500 dark:group-hover:text-yellow-400 group-hover:drop-shadow-md">
                                {{ top3Popular[0].title }}
                            </h3>
                            
                            <div class="flex items-center justify-center gap-3 pt-4 border-t border-yellow-100 dark:border-gray-700">
                                <div class="w-12 h-12 rounded-full border-[3px] border-gray-200 dark:border-gray-600 shadow-lg overflow-hidden bg-white dark:bg-gray-700 group-hover:border-yellow-400 transition-all duration-300 flex-shrink-0">
                                    <PixelAvatar v-if="isPixelAvatar(top3Popular[0].user?.profile_photo_path)" :id="top3Popular[0].user.profile_photo_path" className="w-full h-full" />
                                    <img v-else-if="top3Popular[0].user?.profile_photo_url" :src="top3Popular[0].user.profile_photo_url" class="w-full h-full object-cover">
                                </div>
                                <span class="text-base font-bold text-gray-500 dark:text-gray-400 transition-colors duration-300 group-hover:text-yellow-600 dark:group-hover:text-yellow-500 truncate max-w-[160px]">
                                    {{ top3Popular[0].user?.name }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-if="top3Popular[2]" class="order-3 md:order-3 w-full md:w-[30%] relative group animate-podi delay-200 opacity-0">
                    <div class="absolute -inset-0.5 bg-gradient-to-b from-orange-300 via-orange-200 to-amber-700 rounded-[2.5rem] opacity-0 group-hover:opacity-60 blur transition duration-500"></div>
                    
                    <Link :href="`/rankings/${top3Popular[2].id}`" class="relative block h-full bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border-2 border-gray-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-800/50 hover:-translate-y-2 transition-all duration-500 shadow-xl shadow-orange-500/20 dark:shadow-orange-900/20 group-hover:shadow-orange-500/40 flex flex-col">
                        <div class="absolute top-4 left-4 z-20 w-12 h-12 rounded-full flex items-center justify-center shadow-lg border-2 border-gray-700 group-hover:border-white bg-black text-white group-hover:bg-gradient-to-br group-hover:from-orange-400 group-hover:to-amber-700 transition-all duration-500">
                            <span class="text-xl font-black">3</span>
                        </div>

                        <div class="h-64 overflow-hidden relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent z-10"></div>
                            <img v-if="top3Popular[2].image" :src="`/storage/${top3Popular[2].image}`" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                            <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-4xl">🥉</div>
                        </div>

                        <div class="p-5 relative z-20 flex flex-col flex-grow justify-between text-center">
                            <h3 class="font-bold text-gray-900 dark:text-white text-center text-lg leading-tight line-clamp-2 mb-3 h-[3.2em] flex items-center justify-center transition-colors duration-300 group-hover:text-orange-600 dark:group-hover:text-orange-400">
                                {{ top3Popular[2].title }}
                            </h3>
                            
                            <div class="flex items-center justify-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="w-10 h-10 rounded-full border-2 border-gray-200 dark:border-gray-600 shadow-lg overflow-hidden bg-white dark:bg-gray-700 group-hover:border-orange-400 transition-colors duration-300 flex-shrink-0">
                                    <PixelAvatar v-if="isPixelAvatar(top3Popular[2].user?.profile_photo_path)" :id="top3Popular[2].user.profile_photo_path" className="w-full h-full" />
                                    <img v-else-if="top3Popular[2].user?.profile_photo_url" :src="top3Popular[2].user.profile_photo_url" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 transition-colors duration-300 group-hover:text-orange-700 dark:group-hover:text-orange-300 truncate max-w-[140px]">
                                    {{ top3Popular[2].user?.name }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
              <div v-for="(ranking, index) in currentList" :key="ranking.id" class="group relative bg-white dark:bg-gray-800 rounded-[2rem] overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-red-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/20 dark:hover:shadow-red-500/20 hover:-translate-y-2">
                  
                  <Link :href="`/rankings/${ranking.id}`" class="block h-full flex flex-col">
                      
                      <div class="w-full aspect-[4/3] bg-gray-100 dark:bg-gray-900 overflow-hidden relative">
                          <img 
                              v-if="ranking.image" 
                              :src="`/storage/${ranking.image}`" 
                              alt="Ranking" 
                              class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" 
                          />
                          <div v-else class="w-full h-full flex items-center justify-center text-4xl bg-gray-50 dark:bg-gray-800">
                              {{ activeTab === 'trending' ? '🔥' : (activeTab === 'popular' ? '🏆' : '🆕') }}
                          </div>
                          
                          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

                          <div class="absolute top-3 left-3 bg-black/60 backdrop-blur-md text-white font-black text-sm px-3 py-1 rounded-lg border border-white/20">
                              <span v-if="activeTab === 'latest'">
                                  🕒 {{ timeAgo(ranking.created_at) }}
                              </span>
                              <span v-else>
                                  #{{ activeTab === 'popular' ? index + 4 : index + 1 }}
                              </span>
                          </div>

                          <div v-if="activeTab === 'trending'" class="absolute top-3 right-3 bg-white/90 dark:bg-black/60 backdrop-blur-md text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1 shadow-lg border border-white/20 dark:border-gray-700 text-gray-900 dark:text-white">
                              <span class="text-green-500">▲</span> {{ ranking.recent_votes_count }} vots
                          </div>
                      </div>

                      <div class="p-6 flex-1 flex flex-col justify-between relative">
                          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                          <div>
                              <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight title-clamp group-hover:text-blue-600 dark:group-hover:text-red-500 transition-colors">
                                  {{ ranking.title }}
                              </h3>
                          </div>
                          
                          <div class="mt-4 flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                              <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden ring-2 ring-transparent group-hover:ring-blue-500 dark:group-hover:ring-red-500 transition-all">
                                  <PixelAvatar 
                                      v-if="isPixelAvatar(ranking.user?.profile_photo_path)" 
                                      :id="ranking.user.profile_photo_path" 
                                      className="w-full h-full" 
                                  />
                                  <img 
                                      v-else-if="ranking.user?.profile_photo_url" 
                                      :src="ranking.user.profile_photo_url" 
                                      class="w-full h-full object-cover"
                                  >
                                  <span v-else class="w-full h-full flex items-center justify-center text-xs font-bold">?</span>
                              </div>
                              <span class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                                  {{ ranking.user?.name || 'Anònim' }}
                              </span>
                          </div>
                      </div>
                  </Link>
              </div>
          </div>
          
          <div v-if="currentList.length === 0 && (!top3Popular || top3Popular.length === 0)" class="text-center text-gray-500 mt-4 py-10 bg-gray-50 dark:bg-gray-900 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-xl mb-2">🤔</p>
            <p>Encara no hi ha rànquings en aquesta categoria.</p>
          </div>

        </section>

        <div class="w-full max-w-7xl mx-auto mb-12">
          <AdBanner format="horizontal" class="w-full" />
        </div>

        <div v-if="!isPremium" class="max-w-7xl mx-auto px-6">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black z-0"></div>
                
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500/30 rounded-full blur-[100px] group-hover:bg-yellow-400/40 transition-colors duration-700"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-600/20 rounded-full blur-[100px] group-hover:bg-blue-500/30 transition-colors duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between p-10 md:p-16 gap-8 text-center md:text-left">
                    <div class="max-w-xl">
                        <h3 class="text-3xl md:text-4xl font-black text-white mb-6 leading-tight">
                            Elimina els anuncis i <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-500">millora la teva experiència.</span>
                        </h3>
                        
                        <div class="space-y-4">
                            <p class="text-gray-300 text-lg md:text-xl leading-relaxed">
                                Per només <span class="text-white font-bold border-b-2 border-yellow-500">1,99€</span>, puja de nivell a Rankkatsu:
                            </p>
                            <ul class="text-gray-300 text-base md:text-lg space-y-2 inline-block text-left">
                                <li class="flex items-center gap-2">
                                    <span class="text-yellow-400">🚫</span> Adéu a tota la publicitat
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-yellow-400">✨</span> Insígnia i avatars exclusius
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-yellow-400">❤️</span> Suport directe al projecte
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <Link href="/premium" class="flex-shrink-0 group/btn relative inline-flex items-center justify-center px-8 py-4 bg-yellow-400 hover:bg-yellow-300 text-gray-900 text-lg font-bold rounded-full transition-all duration-300 hover:scale-105 shadow-[0_0_20px_rgba(250,204,21,0.3)] hover:shadow-[0_0_30px_rgba(250,204,21,0.6)]">
                        Passar-me a Premium
                        <svg class="w-5 h-5 ml-2 -mr-1 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </Link>
                </div>
            </div>
        </div>

    </div>
  </AppLayout>
</template>