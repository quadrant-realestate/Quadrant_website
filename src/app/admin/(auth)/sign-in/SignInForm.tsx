"use client";

import { useActionState } from "react";
import { signIn, type SignInState } from "@/actions/auth";

export function SignInForm() {
  const [state, action, pending] = useActionState<SignInState, FormData>(signIn, {});

  return (
    <form action={action}>
      {state.error && (
        <div className="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
          <button type="button" className="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" />
          {state.error}
        </div>
      )}
      <div className="mb-3">
        <label htmlFor="exampleInputEmail1" className="form-label">
          Email Address
        </label>
        <input
          type="email"
          className="form-control"
          name="email_address"
          id="exampleInputEmail1"
          placeholder="Enter your email"
          aria-describedby="emailHelp"
          defaultValue={state.email}
        />
        {state.errors?.email_address && <small className="text-danger">{state.errors.email_address}</small>}
      </div>
      <div className="mb-4">
        <div className="d-flex align-items-center justify-content-between">
          <label htmlFor="exampleInputPassword1" className="form-label">
            Password
          </label>
        </div>
        <input
          type="password"
          className="form-control"
          id="exampleInputPassword1"
          name="password"
          placeholder="Enter your password"
        />
        {state.errors?.password && <small className="text-danger">{state.errors.password}</small>}
      </div>
      <div className="d-flex align-items-center justify-content-between mb-4">
        <div className="form-check">
          <input className="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" defaultChecked />
          <label className="form-check-label text-dark" htmlFor="flexCheckChecked">
            Keep me logged in
          </label>
        </div>
      </div>
      <button type="submit" className="btn btn-dark w-100 py-8 mb-4 rounded-1" disabled={pending}>
        Sign In
      </button>
    </form>
  );
}
