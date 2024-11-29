// src/middleware/worker.js
import { useUserStore } from 'src/stores/modules/userStore';
import { USER_ROLES } from 'src/utils/constants';

export function worker({ next }) {
    const userStore = useUserStore();
    const userRole = userStore.userData.role ? userStore.userData.role.slug : null;

    if (userRole === USER_ROLES.WORKER) {
        return next();
    } else {
        return next({ path: '/page-not-found' });
    }
}
