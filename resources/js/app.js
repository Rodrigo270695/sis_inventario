import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { OhVueIcon, addIcons } from "oh-vue-icons";

import
{
    FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
    PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
    ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
    FaSearch, BiEyeFill, BiEyeSlashFill, GiExitDoor, MdDelete, MdChangecircleRound, MdPassword, MdAddphotoalternateRound, BiUpload,
    GiHamburgerMenu, MdMonitorTwotone, HiSolidPlusSm, HiMinusSm, HiSolidChevronDown, IoClose, OiApps, MdAddbusiness, MdAddcircleRound,
    MdAddlinkSharp, MdFileuploadRound, HiSolidDownload, BiNodePlusFill, HiChevronRight, HiChevronDown, BiArrowLeftRight, RiGpsFill,
    MdAddlocationaltOutlined, IoDesktop, MdGpsfixedTwotone, FaStoreAlt, MdDashboardSharp, BiMouse2, BiPcDisplay, MdMergetypeTwotone, AiPreregistered,
    MdFactory, HiSolidDocumentAdd, HiSolidDocument, BiSendPlus, FaUserFriends, HiUserAdd
} from "oh-vue-icons/icons";

addIcons(
FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
FaSearch, BiEyeFill, BiEyeSlashFill, GiExitDoor, MdDelete, MdChangecircleRound, MdPassword, MdAddphotoalternateRound, BiUpload,
GiHamburgerMenu, MdMonitorTwotone, HiSolidPlusSm, HiMinusSm, HiSolidChevronDown, IoClose, OiApps, MdAddbusiness, MdAddcircleRound,
MdAddlinkSharp, MdFileuploadRound, HiSolidDownload, BiNodePlusFill, HiChevronRight, HiChevronDown, BiArrowLeftRight, RiGpsFill,
MdAddlocationaltOutlined, IoDesktop, MdGpsfixedTwotone, FaStoreAlt, MdDashboardSharp, BiMouse2, BiPcDisplay, MdMergetypeTwotone, AiPreregistered,
MdFactory, HiSolidDocumentAdd, HiSolidDocument, BiSendPlus, FaUserFriends, HiUserAdd
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        app.component("v-icon", OhVueIcon);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
