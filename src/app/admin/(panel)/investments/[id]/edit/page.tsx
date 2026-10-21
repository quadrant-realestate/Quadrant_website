import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { activeCommunities } from "@/lib/admin/developments";
import { activeProperties, getInvestment } from "@/lib/admin/investments";
import { toId } from "@/lib/form";
import { PageHeader } from "../../../_projects/PageHeader";
import { InvestmentForm } from "../../_components/InvestmentForm";

export default async function EditInvestment({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const investment = id ? await getInvestment(id) : null;
  if (!investment) redirect("/admin/projects-record-missing?m=investment");

  const [communities, properties] = await Promise.all([activeCommunities(), activeProperties()]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Edit Investment" crumbs={["Investments", "Edit"]} />
        <InvestmentForm communities={communities} properties={properties} investment={investment} flash={<AdminFlash />} />
      </div>
    </div>
  );
}
