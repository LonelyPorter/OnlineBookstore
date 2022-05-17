package com.example.bookstore;

import android.content.Context;
import android.os.Bundle;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class OrderActivity extends AppCompatActivity {
    String id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // get information from other activity
        id = getIntent().getStringExtra("id");

        // Connect to php to retrieve data and create table
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                getString(R.string.url) + "order.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Context context = OrderActivity.this;
                        TableLayout orderTable = (TableLayout) findViewById(R.id.orderTable);

                        // get orders
                        JSONArray orders = null;
                        try {
                            orders = new JSONArray(response);
                            // parse the order data
                            for (int i = 0; i < orders.length(); i++) {
                                JSONObject order = orders.getJSONObject(i);
                                TableRow row = new TableRow(context);
                                TableRow.LayoutParams layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
                                row.setLayoutParams(layoutParams);

                                TextView orderNumber = new TextView(context);
                                TableRow.LayoutParams l1 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 1);
                                TextView title = new TextView(context);
                                TableRow.LayoutParams l2 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 2);
                                TextView quantity = new TextView(context);
                                TableRow.LayoutParams l3 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 1);
                                // layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT, TableRow.LayoutParams.WRAP_CONTENT, 1);

                                // order#
                                orderNumber.setText(order.getString("orderNumber"));
                                orderNumber.setLayoutParams(l1);
                                orderNumber.setGravity(Gravity.CENTER);
                                orderNumber.setBackground(getDrawable(R.drawable.border));
                                // title
                                title.setText(order.getString("title"));
                                title.setLayoutParams(l2);
                                title.setGravity(Gravity.CENTER);
                                title.setBackground(getDrawable(R.drawable.border));
                                // Quantity
                                quantity.setText(order.getString("quantity"));
                                quantity.setLayoutParams(l3);
                                quantity.setGravity(Gravity.CENTER);
                                quantity.setBackground(getDrawable(R.drawable.border));

                                // add data to row
                                row.addView(orderNumber);
                                row.addView(title);
                                row.addView(quantity);

                                // add row to table
                                orderTable.addView(row, i + 1);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
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
                map.put("id", id);
                return map;
            }
        };

        requestQueue.add(stringRequest);
    }
}