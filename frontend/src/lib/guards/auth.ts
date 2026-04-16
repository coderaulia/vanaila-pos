import { goto } from '$app/navigation';
import { resolve } from '$app/paths';
import type { AppSession, UserRole } from '$types/pos';

type SessionLike = {
	current: AppSession | null;
};

export function requireAuth(session: SessionLike, role?: UserRole): boolean {
	if (!session.current) {
		void goto(resolve('/auth/login'), { replaceState: true });
		return false;
	}

	if (role && session.current.user.role !== role) {
		void goto(resolve('/'), { replaceState: true });
		return false;
	}

	return true;
}
