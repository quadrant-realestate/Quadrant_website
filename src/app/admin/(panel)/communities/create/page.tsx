import { PageHeader } from "../../_projects/PageHeader";
import { CommunityForm } from "../_components/CommunityForm";

export default function CreateCommunity() {
  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Add Community" crumbs={["Communities", "Add"]} />
        <CommunityForm />
      </div>
    </div>
  );
}
