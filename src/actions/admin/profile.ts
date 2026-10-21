"use server";

import { createHash } from "node:crypto";
import { redirect } from "next/navigation";
import { requireAdmin, startSession } from "@/lib/auth";
import { queryOne, update } from "@/lib/db";
import { flash } from "@/lib/flash";
import { str } from "@/lib/form";
import { failed, isEmail, type AdminFormState } from "@/lib/admin/form-state";
import { getAdminProfile } from "@/lib/admin/profile";
import { deleteUpload, isFile, saveUpload } from "@/lib/uploads";

const sha1 = (value: string) => createHash("sha1").update(value).digest("hex");

/** File with a different original name, so saveUpload() produces "<time>_photo_<name>" like the controller. */
const renamed = (file: File, name: string) => new File([file], name, { type: file.type });

// AdminController@profileUpdate (the route pointed to a missing "updateProfile" method; the Blade form posts
// name / email / phone / avatar / profile_photo and the password fields together, which profileUpdate handles).
export async function updateProfile(prev: AdminFormState, form: FormData): Promise<AdminFormState> {
  const session = await requireAdmin();
  const adminId = session.id;

  const name = str(form, "name");
  const email = str(form, "email");
  const phone = str(form, "phone");
  const currentPassword = str(form, "current_password");
  const newPassword = str(form, "new_password");
  const confirmation = str(form, "new_password_confirmation");

  const errors: Record<string, string> = {};
  if (name === null) errors.name = "The name field is required.";
  else if (name.length > 100) errors.name = "The name field must not be greater than 100 characters.";

  if (email === null) errors.email = "The email field is required.";
  else if (!isEmail(email)) errors.email = "The email field must be a valid email address.";
  else {
    const taken = await queryOne("SELECT id FROM admins WHERE email = ? AND id <> ? LIMIT 1", [email, adminId]);
    if (taken) errors.email = "The email has already been taken.";
  }

  if (phone !== null && phone.length > 20) errors.phone = "The phone field must not be greater than 20 characters.";

  if (newPassword !== null) {
    if (newPassword.length < 6) errors.new_password = "The new password field must be at least 6 characters.";
    else if (newPassword !== confirmation) errors.new_password = "The new password field confirmation does not match.";
  }

  if (Object.keys(errors).length > 0) return failed(prev, form, errors);

  const admin = await getAdminProfile(adminId);
  if (!admin) redirect("/admin/sign-in");

  // Handle password change (checked before any file is written, same outcome as the controller)
  let password: string | undefined;
  if (currentPassword !== null) {
    if (sha1(currentPassword) !== admin.password) {
      return failed(prev, form, {}, "Current password is incorrect.");
    }
    if (newPassword !== null) password = sha1(newPassword);
  }

  // Handle avatar upload
  let avatar = admin.avatar;
  const avatarFile = form.get("avatar");
  if (isFile(avatarFile)) {
    await deleteUpload(avatar);
    avatar = await saveUpload(avatarFile, "admin");
  }

  // Handle profile_photo upload
  let profilePhoto = admin.profile_photo;
  const photoFile = form.get("profile_photo");
  if (isFile(photoFile)) {
    await deleteUpload(profilePhoto);
    profilePhoto = await saveUpload(renamed(photoFile, `photo_${photoFile.name}`), "admin");
  }

  await update("admins", adminId, {
    name,
    email,
    phone,
    avatar,
    profile_photo: profilePhoto,
    ...(password ? { password } : {}),
  });

  // Update session
  await startSession({ id: adminId, name, email });

  await flash("success", "Profile updated successfully.");
  redirect("/admin/profile");
}
