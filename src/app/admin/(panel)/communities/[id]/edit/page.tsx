import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { getCommunity } from "@/lib/admin/communities";
import { toId } from "@/lib/form";
import { PageHeader } from "../../../_projects/PageHeader";
import { CommunityForm } from "../../_components/CommunityForm";

export default async function EditCommunity({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const community = id ? await getCommunity(id) : null;
  if (!community) redirect("/admin/projects-record-missing?m=community");

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Edit Community" crumbs={["Communities", "Edit"]} />
        <CommunityForm community={community} flash={<AdminFlash />} />
      </div>
    </div>
  );
}
