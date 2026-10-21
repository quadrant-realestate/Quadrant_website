import "server-only";
import { queryOne } from "@/lib/db";

export type AdminProfile = {
  id: number;
  name: string | null;
  email: string | null;
  phone: string | null;
  avatar: string | null;
  profile_photo: string | null;
  password: string | null;
  created_at: Date | null;
  updated_at: Date | null;
};

export async function getAdminProfile(id: number): Promise<AdminProfile | null> {
  return queryOne<AdminProfile>(
    "SELECT id, name, email, phone, avatar, profile_photo, password, created_at, updated_at FROM admins WHERE id = ? LIMIT 1",
    [id],
  );
}
