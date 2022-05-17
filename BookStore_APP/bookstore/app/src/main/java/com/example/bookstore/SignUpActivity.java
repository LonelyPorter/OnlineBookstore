package com.example.bookstore;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

public class SignUpActivity extends AppCompatActivity {
    EditText emailText;
    EditText pwdText;
    EditText nameText;
    EditText phoneText;
    EditText addressText;
    boolean normal; // 0 is author, 1 is normal


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_up);

        // get variable
        emailText = findViewById(R.id.emailText);
        pwdText = findViewById(R.id.passwordText);
        nameText = findViewById(R.id.nameText);
        phoneText = findViewById(R.id.phoneText);
        addressText = findViewById(R.id.addressText);
        normal = true;
    }


    @SuppressLint("NonConstantResourceId")
    public void onCustomerClicked(View view) {
        // get data
        switch (view.getId()) {
            case R.id.author_btn:
                normal = false;
                break;
            case R.id.normal_btn:
                normal = true;
                break;
        }
    }


    public void onSignUpClicked(View view) {
        String email = emailText.getText().toString();
        String pwd = pwdText.getText().toString();
        String name = nameText.getText().toString();
        String phone = phoneText.getText().toString();
        String address = addressText.getText().toString();

        if (email.isEmpty() || pwd.isEmpty() || name.isEmpty() || phone.isEmpty() || address.isEmpty()) {
            Toast.makeText(this, "Please enter all required fields", Toast.LENGTH_SHORT).show();
            return;
        }

        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "signUp.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Sign Up", "response: " + response);
                        if (response.equals("true")) {
                            Toast.makeText(SignUpActivity.this, "Succeed!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            Toast.makeText(SignUpActivity.this, "Fail: User already exists!", Toast.LENGTH_SHORT).show();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<String, String>();
                map.put("pwd", pwd);
                map.put("name", name);
                map.put("email", email);
                map.put("phone", phone);
                map.put("addr", address);
                if (normal) {
                    map.put("status", "member");
                } else {
                    map.put("status", "author");
                }

                return map;
            }
        };

        requestQueue.add(stringRequest);
    }
}