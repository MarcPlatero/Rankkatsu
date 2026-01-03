<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import PixelAvatar from '@/Components/PixelAvatar.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)

const notificationsOpen = ref(false)
const notifications = computed(() => page.props.auth.user?.notifications || [])
const unreadCount = computed(() => page.props.auth.user?.unread_count || 0)

const markAsRead = () => {
  notificationsOpen.value = !notificationsOpen.value
  
  if (notificationsOpen.value && unreadCount.value > 0) {
    router.post('/notifications/mark-read', {}, { preserveScroll: true })
  }
}

const closeNotifications = (e) => {
  if (!e.target.closest('#notification-btn') && !e.target.closest('#notification-dropdown')) {
    notificationsOpen.value = false
  }
}

const isDropdownOpen = ref(false)

const logout = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/'),
  })
}

const closeDropdown = (e) => {
  if (!e.target.closest('#user-menu-btn') && !e.target.closest('#user-menu-dropdown')) {
    isDropdownOpen.value = false
  }
}

const isPixelAvatar = (path) => {
  return path && typeof path === 'string' && path.startsWith('pixel-')
}

onMounted(() => {
  window.addEventListener('click', closeDropdown)
  window.addEventListener('click', closeNotifications)
})

onUnmounted(() => {
  window.removeEventListener('click', closeDropdown)
  window.removeEventListener('click', closeNotifications)
})
</script>

<template>
  <nav
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-300"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
      
      <Link href="/" class="flex items-center gap-1 shrink-0 mr-4 group">
        <svg 
            viewBox="0 0 1428 884" 
            class="h-10 w-auto transition-transform duration-300 group-hover:scale-105 text-gray-900 dark:text-white"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path style="fill:#aa0000" d="m 697.25625,597.24756 c -2.56666,-3.4279 -8.1579,-9.9799 -12.42497,-14.56 l -7.75832,-8.3274 8.42498,-4.2211 c 26.95024,-13.5029 48.87141,-36.1666 55.04513,-56.9098 l 0.89288,-3 h -19.09973 -19.09974 l 0.4844,-10.6666 c 0.26642,-5.8667 0.11239,-10.6667 -0.34228,-10.6667 -0.45468,0 -4.14156,2.7 -8.19307,6 -4.05151,3.3 -7.57033,6 -7.81961,6 -0.24928,0 -3.97593,-4.65 -8.28145,-10.3333 l -7.82822,-10.3334 -0.66666,50 c -0.60466,45.3491 -0.89853,50.4666 -3.15928,55.0155 -5.46324,10.9929 -15.66277,15.4141 -38.29871,16.6015 l -14.12466,0.7409 -1.66098,-8.5123 c -0.91354,-4.6817 -3.40468,-13.0122 -5.53587,-18.5122 -2.13119,-5.5 -3.87812,-10.3 -3.88207,-10.6667 -0.004,-0.3667 5.80619,-0.4549 12.91139,-0.1961 11.22023,0.4087 13.14851,0.1561 14.6677,-1.9215 1.34221,-1.8356 1.74914,-10.8633 1.74914,-38.8039 v -36.4118 H 619.36238 605.4685 l -0.85462,9 c -3.30056,34.7578 -9.04511,65.3912 -16.62729,88.6666 -3.04584,9.35 -5.78595,17 -6.08913,17 -0.30318,0 -4.14591,-2.3233 -8.5394,-5.163 -4.39349,-2.8396 -11.92484,-6.878 -16.73633,-8.9741 -6.66257,-2.9025 -8.52809,-4.2941 -7.82509,-5.837 6.61356,-14.5149 14.06516,-48.0059 17.12622,-76.9732 2.89949,-27.4384 3.56792,-51.1272 3.79196,-134.386 l 0.2081,-77.3333 50.33333,-0.3494 50.33334,-0.3494 v 86.063 86.0629 l 3,-1.5898 c 4.0044,-2.1222 31.66666,-29.8041 31.66666,-31.6892 0,-0.9924 -4.62222,-1.4815 -14,-1.4815 h -14 v -16.6366 -16.6366 l 24.33334,-0.3634 24.33333,-0.3634 4.36092,-11.0827 c 2.3985,-6.0954 4.01309,-11.6454 3.58798,-12.3333 -0.43201,-0.699 -10.73615,-1.2506 -23.36092,-1.2506 h -22.58798 v -16 -16 h 8 c 4.4,0 8,-0.5828 8,-1.295 0,-2.9559 -8.26532,-23.633 -12.59696,-31.5134 l -4.66777,-8.492 3.63236,-1.8546 c 6.30137,-3.2174 27.31381,-9.0995 28.15767,-7.8824 0.44442,0.641 2.81852,4.0327 5.27578,7.537 4.92683,7.0263 14.86559,27.43 14.86559,30.5183 0,1.1396 -3.92916,3.7152 -9.39753,6.1603 -5.16864,2.3111 -9.07229,4.7282 -8.67478,5.3714 0.62085,1.0046 24.72993,2.1134 24.74413,1.1381 0.003,-0.1949 1.45636,-7.8544 3.23004,-17.0211 1.77369,-9.1666 3.59575,-20.8666 4.04903,-26 1.61462,-18.2851 1.46041,-17.9128 7.09248,-17.1246 32.47836,4.5454 44.64419,7.4235 39.47904,9.3394 -4.74101,1.7586 -5.81658,4.6796 -9.89329,26.8682 -2.33257,12.6956 -3.95982,23.3641 -3.61611,23.7078 0.3437,0.3437 8.35638,0.4564 17.80595,0.2504 l 17.18104,-0.3745 -5.08085,-2.6188 c -3.42253,-1.7641 -4.85629,-3.2873 -4.39283,-4.6667 3.28003,-9.7627 18.23319,-44.7455 19.27455,-45.0926 1.12259,-0.3742 10.40095,2.2633 37.19913,10.5743 5.55487,1.7228 5.48026,3.2888 -0.27119,5.6919 -3.78085,1.5797 -5.59448,3.8928 -10.13857,12.9306 -3.04373,6.0536 -6.97169,13.284 -8.72881,16.0675 -1.75712,2.7834 -3.19477,5.6727 -3.19477,6.4206 0,0.8517 6.22795,1.3599 16.66667,1.3599 h 16.66667 v 16 16 h -26 c -19.9272,0 -25.99296,0.3892 -25.96984,1.6666 0.0166,0.9167 1.96658,6.4667 4.33333,12.3334 l 4.30317,10.6666 26,0.7397 c 14.3,0.4068 26.20923,0.8568 26.46495,1 0.25572,0.1432 0.22665,7.3103 -0.0646,15.927 l -0.52954,15.6667 -15.60207,0.3756 c -8.88011,0.2138 -15.60207,0.9302 -15.60207,1.6628 0,3.5953 28.28467,29.2823 37.47886,34.0368 l 4.0955,2.1179 -7.94096,7.1861 c -4.36753,3.9524 -10.27176,10.8037 -13.12051,15.2251 -2.84876,4.4215 -5.54328,8.0442 -5.98782,8.0507 -0.44455,0.01 -3.55855,-2.0884 -6.92001,-4.655 -3.36146,-2.5667 -6.36315,-4.6667 -6.67041,-4.6667 -0.30726,0 -0.97333,7.95 -1.48016,17.6667 -3.93193,75.381 -9.65297,89.2853 -38.78782,94.2691 -5.13334,0.8781 -16.23334,1.4018 -24.66667,1.1637 l -15.33333,-0.4328 -1.13491,-5.3334 c -0.62419,-2.9333 -2.57419,-10.2211 -4.33333,-16.1952 -1.75914,-5.974 -3.19843,-11.2115 -3.19843,-11.6389 0,-0.4274 7.35,-0.4163 16.33333,0.025 15.39117,0.7555 16.54219,0.6351 19.95408,-2.0884 1.9914,-1.5895 4.24984,-5.1128 5.01874,-7.8295 2.18108,-7.706 5.15414,-27.9818 5.61196,-38.2726 l 0.41522,-9.3334 -17.02648,-0.3733 c -12.9438,-0.2839 -17.14531,0.036 -17.52203,1.3333 -11.22515,38.6601 -23.89108,57.3746 -52.38378,77.3996 -5.69586,4.0031 -24.69893,14.416 -26.03442,14.2659 -0.20164,-0.023 -2.46662,-2.8459 -5.03329,-6.2739 z m 52.5,-124.5183 c 0.45834,-2.2916 0.83334,-8.8916 0.83334,-14.6666 v -10.5 l 5.66666,0.011 c 8.76856,0.018 35.81876,2.5918 37.38094,3.5573 0.82943,0.5126 -0.006,1.9917 -2.06018,3.6468 -2.92702,2.3586 -3.59904,4.263 -4.39376,12.4511 l -0.93822,9.6667 h 28.21919 c 27.53658,0 28.18563,-0.063 26.83178,-2.5924 -0.76308,-1.4258 -2.03726,-2.9941 -2.83151,-3.4849 -2.40258,-1.4849 -16.93494,-21.6562 -22.64098,-31.4263 l -5.35155,-9.1631 h -29.81391 -29.81391 l -3.9882,7.0667 c -5.06698,8.9782 -18.58329,26.8775 -24.8148,32.8615 -2.63169,2.5272 -4.78489,5.0772 -4.78489,5.6667 0,0.5895 7.125,1.0718 15.83334,1.0718 h 15.83333 z m -116.79947,-42.98 0.36719,-20.8533 h -13.36719 -13.36719 v 20.4444 c 0,11.2445 0.30822,20.7527 0.68493,21.1294 0.37671,0.3767 6.22671,0.5607 13,0.4089 l 12.31507,-0.2761 z m 162.96614,-33.9136 c 0,-0.5832 -1.70456,-6.1332 -3.78791,-12.3334 l -3.78792,-11.273 h -6.36714 -6.36714 l -3.84494,11.3327 c -2.11472,6.233 -3.84495,11.783 -3.84495,12.3333 0,0.5504 6.3,1.0007 14,1.0007 7.7,0 14,-0.4771 14,-1.0603 z m -163.33333,-44.9397 v -21.3333 l -13,-0.3817 -13,-0.3816 v 21.1594 c 0,11.6377 0.42091,21.5803 0.93536,22.0948 0.51446,0.5144 6.36446,0.7644 13,0.5555 l 12.06464,-0.3798 z" />
            
            <path style="fill:currentColor" d="m 329.50047,73.106808 c -10.76804,-0.25801 -36.46682,0.489327 -57.10938,1.660157 -42.13545,2.389889 -74.64441,2.618229 -107.13476,0.75 -42.38089,-2.436931 -82.947752,-3.069752 -89.070313,-1.388672 -7.959361,2.18542 -11.183333,5.723284 -10.671875,11.710934 0.664407,7.77822 4.16877,9.3122 26.507813,11.59766 21.069595,2.1556 30.705725,4.951243 39.824215,11.556643 6.56973,4.75909 12.73676,14.00506 15.49805,23.23633 2.9975,10.0209 5.68912,36.95093 6.5293,65.33203 0.41248,13.9333 1.08,32.8333 1.48437,42 1.03971,23.5697 1.02991,352.33798 -0.0117,393.33398 -2.65461,104.4783 -6.72222,132.24674 -21.23633,144.99024 -6.81882,5.9871 -16.66538,9.10553 -36.853509,11.67383 -23.221271,2.9542 -26.666017,4.69911 -26.666017,13.50781 0,3.9959 5.573802,9.46173 10.443359,10.24023 6.927956,1.1076 55.895757,0.45621 79.556637,-1.05859 35.98537,-2.3038 67.92331,-2.32416 108.66602,-0.0684 43.25558,2.3948 74.52475,2.48929 81.9668,0.24609 9.74473,-2.9373 11.52868,-13.03887 3.19726,-18.10546 -2.5471,-1.54901 -9.21712,-3.09916 -17.63086,-4.09766 -28.316,-3.3604 -39.93602,-6.68348 -48.46875,-13.86328 -8.23757,-6.9315 -12.52568,-25.46803 -14.99414,-64.79883 -1.77199,-28.2336 -4.82171,-241.69732 -3.54883,-248.41602 0.71388,-3.7682 1.54211,-4.97184 3.20118,-4.65234 1.2309,0.237 29.54653,36.80331 62.92382,81.25781 66.06392,87.989 72.62744,96.66473 97.17383,128.45703 9.06798,11.7448 25.50679,33.35489 36.5293,48.02149 27.26993,36.2857 39.41112,50.14977 54.6582,62.41797 15.42347,12.4101 33.77774,22.57625 49.6582,27.50195 21.63222,6.7098 27.1175,7.04409 126.66797,7.71289 117.85463,0.7919 134.92662,-0.46527 163.63868,-12.04297 30.47159,-12.2871 57.20905,-37.48041 95.79101,-90.25781 6.16503,-8.4334 16.78298,-22.53203 23.59375,-31.33203 6.81077,-8.8 21.96155,-28.6 33.66995,-44 11.7085,-15.4 25.8911,-34.00069 31.5175,-41.33399 22.4621,-29.2769 39.1532,-51.5837 52.377,-70 13.8262,-19.2553 49.1209,-65.33398 50.043,-65.33398 0.2666,0 1.5245,-0.39852 2.7968,-0.88672 2.187,-0.8393 2.2632,4.05732 1.4082,89.66602 -1.841,184.3275 -4.2921,212.20704 -20.0605,228.08984 -6.927,6.9774 -17.1008,10.17508 -42.1094,13.23828 -9.9,1.2126 -19.6762,3.06388 -21.7246,4.11328 -7.7881,3.9898 -8.105,13.33439 -0.5898,17.39649 5.7569,3.1117 33.0999,3.82314 67.0625,1.74414 46.3847,-2.8394 85.5843,-3.10028 124.955,-0.83008 50.8766,2.9338 78.8238,2.86858 84.9629,-0.19922 6.1157,-3.0561 8.8022,-8.5102 6.6973,-13.5918 -2.132,-5.1473 -5.8291,-6.47834 -22.373,-8.06054 -29.7595,-2.846 -41.5246,-8.8845 -48.7012,-24.9961 -7.0587,-15.847 -9.9241,-37.67368 -11.625,-88.52148 -1.734,-51.8366 -2.5125,-287.04233 -1.2832,-387.82813 1.7046,-139.7526 4.4732,-162.63057 21.5351,-177.9707 9.7808,-8.79376 20.3662,-11.719103 53.3653,-14.744143 13.3017,-1.21938 18.4236,-6.68566 14.8906,-15.89258 -2.8786,-7.501359 -23.1824,-8.743738 -87.8379,-5.374998 -47.5109,2.47544 -84.7454,2.256064 -133.334,-0.785156 -13.2,-0.8262 -32.5155,-1.283948 -42.9238,-1.017578 -16.5387,0.423249 -19.3847,0.829656 -22.582,3.216796 -5.1423,3.839256 -6.6698,9.084646 -3.8829,13.337886 2.9025,4.42983 6.9991,5.84091 21.3887,7.37305 17.198,1.83116 25.8242,3.739993 32.666,7.226563 11.565,5.89351 19.2186,16.38185 23.25,31.85938 4.2744,16.41057 6.6273,79.14093 8.2989,221.26953 l 0.8925,75.83203 -2.7207,-1.45703 c -2.606,-1.3946 -48.7915,-48.82235 -65.1211,-66.87305 -4.3121,-4.7667 -21.3994,-23.36738 -37.9726,-41.33398 -56.81433,-61.5915 -126.78146,-139.05028 -135.70313,-150.23438 -17.74493,-22.24499 -26.92383,-40.87286 -26.92383,-54.63867 0,-11.24072 3.10244,-15.27244 16.73243,-21.748043 11.85087,-5.63034 13.85948,-8.18599 12.06054,-15.35352 -1.66027,-6.615061 -7.22652,-8.018895 -30.79297,-7.765625 -11.73333,0.12611 -32.43333,0.951214 -46,1.833984 -17.26695,1.12354 -33.66566,1.249655 -54.66601,0.421875 -33.36481,-1.31514 -87.18546,-1.500219 -146.66602,-0.505859 -21.63334,0.36166 -51.03398,0.05756 -65.33398,-0.675781 -50.87044,-2.60874 -60.89093,-1.511701 -64.13672,7.025386 -2.31013,6.0761 0.29789,9.12227 12.72852,14.86524 14.84488,6.858363 17.1316,10.188933 16.35546,23.816403 -0.45887,8.05684 -1.49667,11.61509 -6.10546,20.91602 -8.8929,17.94664 -20.65827,32.92574 -55.54102,70.71094 -8.12403,8.8 -23.69555,25.9 -34.60352,38 -10.90798,12.1 -26.47661,29.18886 -34.59765,37.97656 -8.12103,8.7876 -17.48932,18.98887 -20.81836,22.66797 -36.9033,40.7828 -49.70827,54.73757 -72.80859,79.35547 -34.8567,37.1467 -41.1311,43.24003 -43.20704,41.95703 -2.71265,-1.6765 0.61543,-218.30742 4.04493,-263.29102 2.78697,-36.55592 8.23922,-52.78185 20.49804,-60.98828 8.40219,-5.62468 16.50749,-7.940903 36.85742,-10.537113 9.16667,-1.16947 18.30841,-2.96652 20.31446,-3.99218 6.44175,-3.29357 6.76256,-12.42167 0.59765,-17.007817 -3.32957,-2.4769 -5.97424,-2.847722 -23.33398,-3.263672 z m 384.11328,27.570312 10.1543,3.60157 c 20.59657,7.30411 43.0905,20.08494 61.48828,34.9375 10.88898,8.79069 33.92177,30.95093 45.42773,43.70703 3.98186,4.4145 22.5355,24.2145 41.23243,44 18.69693,19.7855 38.45634,40.77197 43.9082,46.63867 5.45186,5.8666 14.17339,15.16797 19.38281,20.66797 5.20943,5.5 14.5225,15.4 20.69531,22 6.1728,6.6 15.75342,16.82094 21.28907,22.71484 5.53565,5.894 16.66383,17.88489 24.73042,26.64649 8.0667,8.7617 19.7712,21.34017 26.0098,27.95117 6.2386,6.611 12.6579,13.86966 14.2656,16.13086 l 2.9239,4.11133 -3.7422,4.55664 c -2.0579,2.5054 -10.6059,13.55468 -18.9961,24.55468 -25.14359,32.9644 -89.56424,115.32202 -131.93166,168.66602 -11.93994,15.0333 -29.52721,37.5333 -39.08203,50 -53.98227,70.4334 -87.37543,102.27304 -125.0293,119.21094 -12.88801,5.7975 -12.85323,5.79618 -24.16601,0.86718 -22.5438,-9.8223 -44.2302,-25.63081 -69.02735,-50.32031 -19.68628,-19.6008 -24.81543,-25.75984 -64.19531,-77.08984 -17.81062,-23.2154 -33.99607,-43.87689 -59.01562,-75.33399 -11.08195,-13.9333 -27.06268,-34.03261 -35.51368,-44.66601 -16.25576,-20.4535 -47.52318,-60.7394 -72.95507,-94 -8.41089,-11 -16.81786,-21.65935 -18.6836,-23.68555 l -3.39258,-3.68359 5.99219,-6.31641 c 6.19835,-6.5323 47.45908,-50.22112 65.14844,-68.98242 5.53148,-5.8667 13.95795,-14.88793 18.72461,-20.04883 13.09489,-14.178 43.08144,-46.21711 57.33398,-61.25781 6.96666,-7.3519 24.04042,-25.66391 37.94141,-40.69531 31.32783,-33.8753 65.47177,-68.04927 76.05859,-76.12305 23.59108,-17.99115 46.24691,-30.77505 63.84375,-36.02344 z" />
        </svg>
      </Link>

      <div class="hidden md:flex items-center space-x-5 text-sm font-medium">
        <Link href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</Link>
        <Link href="/profile" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">Perfil</Link>
        <Link href="/rankings" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Rankings</Link>
        
        <Link href="/premium" class="text-gray-700 dark:text-gray-300 hover:text-yellow-500 dark:hover:text-yellow-400 transition">
          Premium
        </Link>

        <Link href="/rankings/create" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">Create</Link>
        <Link href="/settings" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Configuració</Link>
        <Link href="/about" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">About</Link>
      </div>
      
      <div class="flex items-center gap-4">
        
        <template v-if="!user">
          <div class="flex items-center gap-3">
            <Link href="/register" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg transition shadow-sm">
                Register
            </Link>
            <Link href="/login" class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 rounded-lg transition shadow-sm">
                Login
            </Link>
          </div>
        </template>

        <template v-else>
          <div class="relative mr-2">
            <button 
                id="notification-btn"
                @click="markAsRead" 
                class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition focus:outline-none"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                
                <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 text-[8px] text-white font-bold items-center justify-center"></span>
                </span>
            </button>

            <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div 
                    v-if="notificationsOpen"
                    id="notification-dropdown"
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50 overflow-hidden"
                >
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 font-bold text-gray-700 dark:text-gray-200 flex justify-between items-center">
                        <span>Notificacions</span>
                        <span v-if="unreadCount > 0" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ unreadCount }} noves</span>
                    </div>

                    <div v-if="notifications.length === 0" class="px-4 py-6 text-center text-gray-500 text-sm">
                        No tens notificacions recents. 💤
                    </div>

                    <div v-else class="max-h-80 overflow-y-auto">
                        <Link 
                            v-for="notification in notifications" 
                            :key="notification.id"
                            :href="`/rankings/${notification.data.ranking_id}`"
                            class="block px-4 py-3 transition border-b border-gray-100 dark:border-gray-700/50 last:border-0 relative group"
                            :class="[
                                !notification.read_at 
                                    ? 'bg-blue-50/80 dark:bg-blue-900/20' 
                                    : 'hover:bg-gray-50 dark:hover:bg-gray-700/50'
                            ]"
                        >
                            <div class="flex items-start gap-3">
                                
                                <span v-if="!notification.read_at" class="absolute top-4 right-3 w-2 h-2 rounded-full bg-blue-500"></span>

                                <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <PixelAvatar 
                                        v-if="isPixelAvatar(notification.data.user_avatar)" 
                                        :id="notification.data.user_avatar" 
                                        className="w-full h-full"
                                    />
                                    <img 
                                        v-else-if="notification.data.user_avatar_url" 
                                        :src="notification.data.user_avatar_url" 
                                        class="w-full h-full object-cover"
                                    >
                                    <span v-else class="w-full h-full flex items-center justify-center font-bold text-xs text-gray-500">
                                        {{ notification.data.user_name.charAt(0) }}
                                    </span>
                                </div>

                                <div class="text-sm pr-4">
                                    <p class="text-gray-800 dark:text-gray-200 leading-snug">
                                        <span class="font-bold">{{ notification.data.user_name }}</span>
                                        {{ notification.data.message }}
                                        <span class="font-medium text-blue-600 dark:text-red-400 block truncate max-w-[220px]">
                                            "{{ notification.data.ranking_title }}"
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ notification.created_at }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </transition>
          </div>

          <div class="relative">
            
            <button 
              id="user-menu-btn"
              @click="isDropdownOpen = !isDropdownOpen"
              class="flex items-center gap-3 p-1 pr-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/50 max-w-[200px] sm:max-w-xs cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-700"
            >
              <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 dark:border-gray-600 shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                
                <PixelAvatar 
                  v-if="isPixelAvatar(user.profile_photo_path)" 
                  :id="user.profile_photo_path" 
                  className="w-full h-full"
                />

                <img 
                  v-else-if="user.profile_photo_url" 
                  :src="user.profile_photo_url" 
                  alt="Avatar" 
                  class="w-full h-full object-cover"
                >

                <div v-else class="text-blue-600 dark:text-blue-300 font-bold text-sm">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>

              </div>

              <div class="flex flex-col items-start truncate hidden sm:flex">
                <div class="flex items-center gap-1.5 w-full">
                  <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 truncate max-w-[100px]" :title="user.name">
                    {{ user.name }}
                  </span>
                  
                  <span v-if="user.is_premium" class="shrink-0 px-1.5 py-[1px] rounded text-[9px] font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm uppercase leading-none">
                    Premium
                  </span>
                </div>
              </div>

              <svg :class="{'rotate-180': isDropdownOpen}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div 
                v-if="isDropdownOpen"
                id="user-menu-dropdown"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50 overflow-hidden"
              >
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 sm:hidden">
                    <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                </div>

                <Link 
                  v-if="user.is_admin" 
                  href="/admin/moderation" 
                  class="block px-4 py-2 text-sm font-medium transition flex items-center gap-2 text-blue-600 hover:bg-blue-50 dark:text-red-400 dark:hover:bg-red-900/20"
                >
                  🛡️ Moderació
                </Link>

                <button 
                  @click="logout" 
                  class="w-full text-left px-4 py-2 text-sm font-semibold transition flex items-center gap-2 text-blue-600 hover:bg-blue-50 dark:text-red-400 dark:hover:bg-red-900/20"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                  Tancar sessió
                </button>
              </div>
            </transition>

          </div>
        </template>

      </div>
    </div>
  </nav>
</template>