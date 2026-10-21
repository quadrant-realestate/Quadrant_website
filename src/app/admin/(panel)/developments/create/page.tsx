import { activeCommunities } from "@/lib/admin/developments";
import { PageHeader } from "../../_projects/PageHeader";
import { DevelopmentForm } from "../_components/DevelopmentForm";

export default async function CreateDevelopment() {
  const communities = await activeCommunities();

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Add Development" crumbs={["Developments", "Add"]} />
        <DevelopmentForm communities={communities} />
      </div>
    </div>
  );
}
